<?php
/*****************************************************************************
 * MY_controller.php : 
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

class MY_Controller extends Controller {

    var $header_section_list  = array();
	var $usuario_tipo = '';

    function MY_Controller()
    {
        parent::Controller();
		
        $this->load->helper('url');            //Este helper nos da las funcionalidades de base_url, current_url, uri_string
		$this->load->library('session');       //Informacion de la sesion
		
		
		$controller = $this->uri->segment(1);
		if (!$controller)
			$controller = "index";
		$operation = $this->uri->segment(2);
		if (!$operation)
			$operation = "index";
			
			
		//Log
		if (LOG_ENABLED and $operation != 'channel_list_refresh' and $operation != 'viewers_refresh' and $operation != 'news')
		{
			$myFile = LOG_ROUTE."/log-".date('Y-m-d').".txt";
			$fh = fopen($myFile, 'a');
			if ($fh != null)
			{
				fwrite($fh, date('Y-m-d H:i:s')."\t"."REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR']."\t"."REQUEST_URI: ".$_SERVER['REQUEST_URI']."\t");
				fwrite($fh, 'GET: {');
				foreach ($_GET as $key => $value)
					fwrite($fh, $key.' => '.$value."\t");
				fwrite($fh, '} POST: {');
				foreach ($_POST as $key => $value)
					fwrite($fh, $key.' => '.$value."\t");
				fwrite($fh, "}\t");
				fwrite($fh, "TOKEN: ". $this->session->userdata('user_token')."\t");
				fwrite($fh, "HTTP_USER_AGENT: ". $_SERVER['HTTP_USER_AGENT']."\t");
				if (strpos(trim($_SERVER['HTTP_REFERER']), $_SERVER['SERVER_NAME']) === false)
					fwrite($fh, "HTTP_REFERER: ". $_SERVER['HTTP_REFERER']."\t");
				fwrite($fh, "\n\n");
				fclose($fh);
			}
		}

		if ($controller != 'installer')
		{
			if (!file_exists(APPPATH.'config/database.php'))
				redirect(base_url().'installer/');
			$this->load->database('startersuite');
			$this->load->model('users');
			$user_type = 'guest';
			
			$info = $this->users->get_token($this->session->userdata('user'), $this->session->userdata('user_token'), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
			if ($info == FALSE)
			{
				$this->session->set_userdata('user', '');
				$this->lang->load('global', LANGUAGE);
			}
			else
			{
				$this->lang->load('global', $info->language);
				$this->session->set_userdata('language'    , $info->language);
				$this->session->set_userdata('news_closed'    , $info->news_closed);
				$this->session->set_userdata('user_type'    , $info->type);
				$user_type = $info->type;
				$this->users->update_last_activity($this->session->userdata('user'), $info->token);
			}
			
			if (!$this->users->is_allowed($user_type, $controller, $operation))
				if ($user_type != 'guest')
					redirect(base_url().'login/not_allowed');
				else
					redirect(base_url().'login/');
		}
		else
		{
			$this->session->set_userdata('user', '');
			$this->session->set_userdata('user_type', 'guest');
			$this->lang->load('global', LANGUAGE);
		}
    }
}

?>