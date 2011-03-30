<?php
/*****************************************************************************
 * broadcaster.php : 
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

class Broadcaster extends MY_Controller {

    var $channel_key = 'Broadcaster_channelKey';

    function Broadcaster()
	{
		parent::MY_Controller();
	}

	function index()
	{
        
        $channel_id = $this->session->userdata($this->channel_key);
        if($channel_id==""){
            $channel_id = uniqid("", TRUE);
            $this->session->set_userdata($this->channel_key, $channel_id);
        }
        $data["channel_id"] = $channel_id;
		$data['hide_tabs'] = true;
        $this->load->view('broadcaster',$data);

    }

    function start(){
		$this->load->library('validations');
        $this->load->model('m_channel_list');
        $channel['hosted_channel_id']             = $this->input->xss_clean($_REQUEST['id']);
        $channel['hosted_channel_name']           = $this->input->xss_clean($_REQUEST['name']);
        $channel['hosted_channel_tracker_url']    = base_url()."btv_tracker/announce";
        $channel['hosted_channel_bitrate']        = STREAM_BITRATE;
        $channel['hosted_channel_chunk_size']     = CHUNK_SIZE;
		$channel['hosted_channel_broadcaster_ip'] = $_SERVER['REMOTE_ADDR'];
		$channel['hosted_channel_del_token']      = $this->validations->get_token();
        $this->m_channel_list->add_or_replace($channel);

        header("Content-Type: application/json");
        $datos = array("ok"=>true, "del_token"=>$channel['hosted_channel_del_token']);
        print json_encode($datos);
    }

	function stop()
	{
		$channel['hosted_channel_id']             = $_REQUEST['id'];
		$channel['hosted_channel_broadcaster_ip'] = $_SERVER['REMOTE_ADDR'];
		$channel['hosted_channel_del_token']      = $_REQUEST['del_token'];		
        $this->load->model('m_channel_list');
		$data['channel_list'] = $this->m_channel_list->delete_web_broadcast($channel);
	}
}
?>
