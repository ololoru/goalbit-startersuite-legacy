<?php
/*****************************************************************************
 * login.php : 
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

class Login extends MY_Controller {

	function Login()
	{
		parent::MY_Controller();	
	}
	
	function change_settings()
	{
		$data['first'] = true;
		$this->load->model('users');
		$data['user'] = $this->users->get_data($this->session->userdata('user'));
		
		$this->load->view('change_settings', $data);
	}
	
	function change_settings_2()
	{
		$this->load->library('validations');
		$data['first'] = false;
		$this->load->model('users');
		$LastP   = trim($_POST['LastP']);
		$NewP    = trim($_POST['NewP']);
		$NewP2   = trim($_POST['NewP2']);
		$language = trim($_POST['language']);
		if (trim($_POST['newsClosed']) == 'true')
			$news_closed = 1;
		else
			$news_closed = 0;
		$user     = $this->session->userdata('user');
		$data['user'] = $this->users->get_data($this->session->userdata('user'));
		
		if ($LastP != '' or $NewP != '' or $NewP2 != '')
		{
			if (!$this->users->check_user($user, $this->validations->translate_password($LastP)))
				$data['error']['LastP'] = $this->lang->line('Incorrect!');
			elseif ($NewP != $NewP2)
				$data['error']['NewP2'] = $this->lang->line('Confirmation does not match!');
			elseif ($NewP == '' or strlen($NewP) > 12 or strlen($NewP) < 8)
				$data['error']['NewP2'] = $this->lang->line('New password must be between 8 and 12 characters!');
			else
				$this->users->change_password($user, $this->validations->translate_password($NewP));
		}
		
		if ($this->users->get_token($user, $this->session->userdata('user_token'), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']) != FALSE and ($language == 'english' or $language == 'spanish'))
			$this->users->update_settings($user, $language, $news_closed);
			
		$this->load->view('change_settings', $data);
	}
	
	function news_close()
	{
		$user = $this->session->userdata('user');
		$this->load->model('users');
		if ($this->users->get_token($user, $this->session->userdata('user_token'), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']) != FALSE)
			$this->users->close_news($user);
	}
	
	
	function log_me_out()
	{
		$this->load->model('users');
		$token = $this->users->delete_token($this->session->userdata('user'), $this->session->userdata('user_token'));
		$this->session->set_userdata('authenticated', '0');
		$this->session->set_userdata('user', '');
		$this->session->set_userdata('user_type', 'guest');
		redirect(base_url());
	}
	
	function index()
	{
		$this->log_me_in();
	}
	
	function log_me_in()
	{
		$data['hide_tabs'] = true;
		$this->load->library('validations');
		$user = $_POST['user'];
		if ($user == '')
			$this->load->view('login', $data);
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$this->load->model('users');
			$password = $this->validations->translate_password($_POST['password']);
			if (!$this->users->check_user($user, $password))
			{
				$this->session->set_userdata('authenticated', '0');
				$data['message'] = $this->lang->line('Incorrect!');
				$this->load->view('login', $data);
			}
			else
			{
				$this->session->set_userdata('authenticated', '1');
				$token = $this->validations->get_token();
				$this->session->set_userdata('user', $user);
				$this->session->set_userdata('user_token', $token);
				$this->users->set_user_token($user, $token, $ip, $useragent);
				redirect(base_url());		
			}
		}
	}
	
	function not_allowed()
	{
		$this->load->view('not_allowed', $data);
	}
}
?>