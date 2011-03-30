<?php
/*****************************************************************************
 * channel_list.php : 
 *****************************************************************************
 * Copyright (C) 2008-2011 The Goalbit Team
 *
 * Authors:    Andres Barrios <andres dot barrios at goalbit-solutions dot com>
 *			   Matias Barrios <matias dot barrios at goalbit-solutions dot com>
 *			   Daniel De Vera <daniel dot de dot vera at goalbit-solutions dot com>
 * 			   Pablo Rodriguez Bocca <pablo dot rodriguez at goalbit-solutions dot com>
 *			   Claudia Rostagnol <claudia dot rostagnol at goalbit-solutions dot com>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.

 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.

 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *****************************************************************************/
?>
<?php

require_once(APPPATH.'libraries/MY_controller.php');

class Channel_list extends MY_Controller {

	function Channel_list()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
		$this->load->library('validations');
		
		if (isset($this->data['is_refresh']))
			$data['is_refresh'] = $this->data['is_refresh'];
		if (isset($this->data['message']))
			$data['message'] = $this->data['message'];
		$data['tab'] = 'channel_list';
		
		$this->load->model('btv_channel_peer_model');
		$this->btv_channel_peer_model->update_peers();
		$tracker_list = $this->btv_channel_peer_model->get_list_stats();
		foreach ($tracker_list as $index => $element)
			$data['tracker_stats'][$index] = $element;

		$this->load->model('m_channel_list');
		$data['channel_list'] = $this->m_channel_list->get_list();
		
		foreach ($data['channel_list'] as $index => $channel)
		{
			$unsigned_char_arr = unpack('C*', sha1(trim($channel['hosted_channel_id']), true)); 
			$channel_info_hash = '';
			for ( $i = 1; $i <= count($unsigned_char_arr); $i ++ )
				$channel_info_hash .= sprintf('%x', $unsigned_char_arr[$i]); 
			$data['channel_ids'][$channel['hosted_channel_id']] = $this->validations->find_similar($tracker_list, $channel_info_hash);
		}
		
		$this->load->view('channel_list', $data);
	}
	
	function channel_list_refresh()
	{
		$this->data['is_refresh'] = true;
		echo 'ok ';
		$this->index();
	}
	
	function viewers()
	{
		if (isset($this->data['is_refresh']))
			$data['is_refresh'] = $this->data['is_refresh'];
		$data['tab'] = 'viewers';
		
		$this->load->library('validations');
		$this->load->model('btv_channel_peer_model');
		$this->btv_channel_peer_model->update_peers();
		
		$data['tracker_stats'] = $this->btv_channel_peer_model->get_list_stats();
		
		$this->load->model('m_channel_list');
		$data['channel_list'] = $this->m_channel_list->get_list();
		
		foreach ($data['channel_list'] as $index => $channel)
		{
			$unsigned_char_arr = unpack('C*', sha1(trim($channel['hosted_channel_id']), true)); 
			$channel_info_hash = '';
			for ( $i = 1; $i <= count($unsigned_char_arr); $i ++ )
				$channel_info_hash .= sprintf('%x', $unsigned_char_arr[$i]); 
			$channel_id = $this->validations->find_similar($data['tracker_stats'], $channel_info_hash);
			$data['channel_ids'][$channel_id]['id'] = $channel['hosted_channel_id'];
			$data['channel_ids'][$channel_id]['name'] = $channel['hosted_channel_name'];
			$data['channel_ids'][$channel_id]['thumb'] = $channel['hosted_channel_thumb'];
		}
		$this->load->view('viewers', $data);
	}
	
	function viewers_refresh()
	{
		$this->data['is_refresh'] = true;
		echo 'ok ';
		$this->viewers();
	}
	
	function upload()
	{
		$this->load->view('upload', $data);
	}
	
	function upload_file()
	{
		$this->data['message'] = '';
		if ($_FILES["file"]["error"] != 0)
			$this->data['message'] = $this->lang->line('Error uploading the file');
		else if (strpos($_FILES["file"]["name"], "\0") !== FALSE) 
			die('');
		else if (strrpos($_FILES["file"]["name"], ".goalbit") !== strlen($_FILES["file"]["name"]) - strlen(".goalbit"))
			$this->data['message'] = $this->lang->line('File uploaded type error');
		else
		{	
			$this->load->library('xml');
			$channel_list =  $this->xml->xml2array(file_get_contents($_FILES["file"]["tmp_name"])); 
			if (isset($channel_list) and isset($channel_list['channel_list']) and isset($channel_list['channel_list']['channel']) and isset($channel_list['channel_list']['channel_attr']) and isset($channel_list['channel_list']['channel']['chunk_size']) and isset($channel_list['channel_list']['channel']['tracker_url']) and isset($channel_list['channel_list']['channel']['bitrate']) and isset($channel_list['channel_list']['channel']['name']) and isset($channel_list['channel_list']['channel_attr']['id']))
			{
				$this->load->model('m_channel_list');
				$channel['hosted_channel_id']          = $channel_list['channel_list']['channel_attr']['id'];
				$channel['hosted_channel_name']        = $channel_list['channel_list']['channel']['name'];
				$channel['hosted_channel_tracker_url'] = $channel_list['channel_list']['channel']['tracker_url'];
				$channel['hosted_channel_bitrate']     = $channel_list['channel_list']['channel']['bitrate'];
				$channel['hosted_channel_chunk_size']  = $channel_list['channel_list']['channel']['chunk_size'];
				if (is_string($channel_list['channel_list']['channel']['thumb']))
					$channel['hosted_channel_thumb']       = $channel_list['channel_list']['channel']['thumb'];
				$channel['hosted_channel_broadcaster_ip'] = $_SERVER['REMOTE_ADDR'];
				$this->m_channel_list->add_or_replace($channel);
			}
			else
				$this->data['message'] = $this->lang->line('Content error');
		}
		$this->index();
	}
	
	function delete()
	{
		$channel['hosted_channel_id'] = $_POST['Id'];
		
		$this->load->model('m_channel_list');
		$data['channel_list'] = $this->m_channel_list->delete($channel);
	}
	
	function embeb_code($Id)
	{
		$channel['hosted_channel_id'] = $Id;

		$this->load->model('m_channel_list');
		$channel = $this->m_channel_list->get($channel['hosted_channel_id']);
		
		if (count($channel) == 0)
			return;
				
		$data['embeb_code'] = htmlspecialchars('<script src="'.base_url().'channel_list/get_js/goalbit.js'.'"></script>').'<br/>'.htmlspecialchars('<div id="goalbit1"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			url="'.base_url().'channel_list/get_goalbit_file/'.$channel[0]['hosted_channel_id'].'"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			width="640px"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			height="360px"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			autoplay="false"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			fullscreen="false"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			aspectRatio=""').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			minimumAcceptVersion="'.urlencode(WEBPLUGINS_VERSION).'"').'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars('
			maximumAcceptVersion="'.urlencode(WEBPLUGINS_VERSION).'"></div>').'<br/>'.htmlspecialchars('<script>window.onLoad = loadGoalBitPlayer("goalbit1");</script>');

		$this->load->view('embeb_code', $data);
	}
	function get_goalbit_file($hosted_channel_id)
	{
		$this->load->model('m_channel_list');
		$data['channel'] = $this->m_channel_list->get($hosted_channel_id);
		if (count($data['channel']) == 0)
			return;
		header ("content-type: text/xml"); 
		$this->load->view('goalbit_file', $data);
	}
	
	function get_js($file)
	{
		if ($file != 'goalbit.js')
			return;
		header("Content-type: text/javascript");
		$this->load->view('goalbit_js', $data);
	}
	
	function iframe_code($hosted_channel_id)
	{
		$this->load->model('m_channel_list');
		$data['channel'] = $this->m_channel_list->get($hosted_channel_id);
		if (count($data['channel']) == 0)
			return;

		$this->load->view('iframe_code', $data);
	}
	
	function get_html($hosted_channel_id)
	{
		$this->load->model('m_channel_list');
		$data['channel'] = $this->m_channel_list->get($hosted_channel_id);
		if (count($data['channel']) == 0)
			return;
			
		$this->load->view('get_html', $data);
	}
	
	function register_goalbit_file()
	{
		$this->load->model('users');
		$this->load->library('validations');
		$password = $this->validations->translate_password($this->input->xss_clean($_POST['2']));
		$exists = $this->users->check_user($this->input->xss_clean($_POST['1']), $password);
		if (!$exists)
			echo 'ERROR';
		else
		{
			$this->load->model('m_channel_list');
			$channel['hosted_channel_id']             = $this->input->xss_clean($_POST['id']);
			$channel['hosted_channel_name']           = $this->input->xss_clean($_POST['id']);
			$channel['hosted_channel_tracker_url']    = base_url()."btv_tracker/announce";
			$channel['hosted_channel_bitrate']        = $this->input->xss_clean($_POST['bitrate']);
			$channel['hosted_channel_chunk_size']     = $this->input->xss_clean($_POST['chunksize']);
			$channel['hosted_channel_broadcaster_ip'] = $_SERVER['REMOTE_ADDR'];
			$this->m_channel_list->add_or_replace($channel);
			echo 'OK';
		}
	}
	
	function scan_port($ip, $port)
	{
		$this->load->model('btv_channel_peer_model');
		if (!$this->btv_channel_peer_model->exists_peer($ip, $port))
			return;
		
		if (($this->session->userdata('user_type') != 'admin') and (trim($ip) != trim($_SERVER['REMOTE_ADDR'])))
		{
			$this->load->view('not_allowed');
			return;
		}

		$fp = fsockopen($ip,$port,$errno,$errstr,6); 
		if($fp) 
		{ 
			echo $this->lang->line('Opened'); 
			fclose($fp); 
		} 
		else 
			echo $this->lang->line('Closed'); 
		flush(); 
	}
	
	function channel_details($hosted_channel_id)
	{
		$this->load->model('m_channel_list');
		$data['channel'] = $this->m_channel_list->get($hosted_channel_id);
		$this->load->view('channel_details', $data);
	}
	
	function news($news_id = '')
	{	
		$this->load->model('m_channel_list');
		$news = $this->m_channel_list->get_next_news($this->session->userdata('language'), $news_id);

		header("Content-Type: application/json");
		if (count($news) != 0)
			$datos = array("id"=>$news[0]['news_id'], "text"=>$news[0]['news_text'], "remaining"=>(count($news) - 1 + ($news_id != '')));
		else
			$datos = array();
        print json_encode($datos);
	}
	
}
?>