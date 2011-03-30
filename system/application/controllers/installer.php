<?php
/*****************************************************************************
 * installer.php : 
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

class Installer extends MY_Controller {

	function Installer()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
		if (file_exists(APPPATH.'config/database.php'))
			redirect(base_url());
		$data['hide_tabs'] = true;
		$this->load->view('installer', $data);
	}
	
	function install()
	{
		$data['hide_tabs'] = true;
		if (file_exists(APPPATH.'config/database.php'))
			redirect(base_url());

		$link = mysql_connect($_POST['url'], $_POST['user'], $_POST['password']);
		if (!$link) 
			$data['message'] = $this->lang->line('Not connected : ') . mysql_error();
		else
		{
			// Temporary variable, used to store current query
			$templine = '';
			// Read in entire file
			$lines = file(base_url().'INSTALL/startersuite.sql');
			if ($lines == null)
				$data['message'] = 'File not found: '.base_url().'INSTALL/startersuite.sql';
			else
			{
				$this->load->library('Utils');
				$random_pass = $this->utils->get_random_password(8, 8, TRUE, TRUE, FALSE);
				
				//Corremos el script
				// Loop through each line
				foreach ($lines as $line)
				{
					// Skip it if it's a comment
					if (substr($line, 0, 2) == '--' || $line == '')
						continue;
				 
					// Add this line to the current segment
					$templine .= $line;
					// If it has a semicolon at the end, it's the end of the query
					if (substr(trim($line), -1, 1) == ';')
					{
						//Si es la de crear el password entonces ponemos nuevo password
						if(strpos($templine, "GRANT INSERT,UPDATE,DELETE,SELECT ON startersuite.* TO 'startersuite'@'localhost' IDENTIFIED BY 'startersuite'") != 0)
							$templine = str_replace("IDENTIFIED BY 'startersuite'", "IDENTIFIED BY '".$random_pass."'", $templine);
						
						// Perform the query
						mysql_query($templine) or $data['message'] .= $this->lang->line('Error performing query').' \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />';
						// Reset temp variable to empty
						$templine = '';
					}
				}
				
				//Creamos el archivo database.php con credenciales randomicas
				$str=implode(file(APPPATH.'config/database.template.php'));
				$fp=fopen(APPPATH.'config/database.php','w');
				if ($fp == FALSE)
					$data['message'] .= $this->lang->line('Could not complete the installation, file').'<br/><br/>
					
					'.APPPATH.'config/database.php'.'<br/><br/>
					
					'.$this->lang->line('could not be created. You can create this file manually by copying and renaming it from').'<br/><br/>
					
					'.APPPATH.'config/database.template.php<br/><br/>
					
					'.$this->lang->line('and setting:').'<br/><br/>
					
					'."\$db['startersuite']['password'] = \"".$random_pass."\";".'<br/><br/>
					
					'.$this->lang->line('Once this is done just').' <a class="operations" href="'.base_url().'">'.$this->lang->line('Go to the main page').'</a> '.$this->lang->line('and acces the aplication with').'<br/><br/>
					'.$this->lang->line('User').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= admin<br/>
					'.$this->lang->line('Password').'&nbsp;= adminadmin<br/><br/>
					
					'.$this->lang->line('CHANGE IF AFTER LOGGING IN').'<br/><br/>';
				else
				{
					$str=str_replace("\$db['startersuite']['password'] = \"startersuite\";","\$db['startersuite']['password'] = \"".$random_pass."\";", $str);
					fwrite($fp,$str,strlen($str));
					fclose($fp);
					$data['installed'] = TRUE;
				}
			}
		}
		$this->load->view('installer', $data);
	}
	
}
?>