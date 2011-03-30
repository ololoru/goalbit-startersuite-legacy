<?php
/*****************************************************************************
 * index.php : 
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

/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
	//error_reporting(E_ALL);
	error_reporting(E_ERROR);

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
	$system_folder = "system";

/*
|---------------------------------------------------------------
| APPLICATION FOLDER NAME
|---------------------------------------------------------------
|
| If you want this front controller to use a different "application"
| folder then the default one you can set its name here. The folder 
| can also be renamed or relocated anywhere on your server.
| For more info please see the user guide:
| http://codeigniter.com/user_guide/general/managing_apps.html
|
|
| NO TRAILING SLASH!
|
*/
	$application_folder = "application";

/*
|===============================================================
| END OF USER CONFIGURABLE SETTINGS
|===============================================================
*/


/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| FCPATH	- The full server path to THIS file
| SELF		- The name of THIS file (typically "index.php)
| BASEPATH	- The full server path to the "system" folder
| APPPATH	- The full server path to the "application" folder
|
*/
define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('FCPATH', __FILE__);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_folder.'/');

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */