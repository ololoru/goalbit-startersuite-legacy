<?php
/*****************************************************************************
 * users.php : 
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

class Users extends Model
{

	function Users()
	{
		parent::Model();
	}	
	
	function check_user($user, $password)
	{
		$sql = 'SELECT userid
                FROM users
                WHERE userid = ? AND password = ?';
        $query = $this->db->query($sql, array($user, $password));
		return ($query->num_rows() == 1);
	}
	
	
	function set_user_token($user, $token, $ip, $useragent)
	{
		$sql = 'INSERT INTO user_session (userid, token, ip, useragent, lastactivity)
				VALUES (?, ?, ?, ?, NOW())';
		$query = $this->db->query($sql, array($user, $token, $ip, $useragent));
	}
	
	function delete_old_sessions($user)
	{
        $sql = 'DELETE FROM user_session
                WHERE ( UNIX_TIMESTAMP() - UNIX_TIMESTAMP(lastactivity) ) > '. SESS_EXPIRATION;
        $this->db->query($sql);
	}
	
	function delete_token($user, $token)
	{
        $sql = 'DELETE FROM user_session
                WHERE userid = ?
				AND token = ?';
        $this->db->query($sql, array($user, $token));
	}
	
	function get_token($user, $token, $ip, $useragent)
	{
		$this->delete_old_sessions($user);
		
		$sql = 'SELECT token, ip, useragent, lastactivity, language, news_closed, type
                FROM user_session
				NATURAL JOIN users
                WHERE userid = ?
				AND token = ?
				AND ip = ?
				AND useragent = ?
				LIMIT 0,1';
        $query = $this->db->query($sql, array($user, $token, $ip, $useragent));
		if ($query->num_rows() == 1)
			return $query->row();
		else
			return false;
	}
	
	function update_last_activity($user, $token)
	{
        $sql = 'UPDATE user_session 
				SET lastactivity = NOW() 
				WHERE userid = ? AND token = ?';
        $this->db->query($sql, array($user, $token));
	}
	
	function change_password($user, $password)
	{
		$sql = 'UPDATE users SET password = ? WHERE userid = ?';
		$query = $this->db->query($sql, array($password, $user));
		return ($query == 1);
	}
	
	function update_settings($user, $language, $news_closed)
	{
		$sql = 'UPDATE users SET language = ?, news_closed =? WHERE userid = ?';
		$query = $this->db->query($sql, array($language, $news_closed, $user));
		return ($query == 1);
	}
	
	function get_data($user)
	{
		$sql = 'SELECT language, news_closed
                FROM users
                WHERE userid = ?';
        $query = $this->db->query($sql, array($user));
		return $query->result_array();
	}
	
	function close_news($user)
	{
		$sql = 'UPDATE users SET news_closed = 1 WHERE userid = ?';
		$query = $this->db->query($sql, array($user));
		return ($query == 1);
	}
	
	function is_allowed($user_type, $controller, $operation)
	{
		if ($user_type == 'admin')
			return true;
			
		$sql = 'SELECT user_type, controller, operation
                FROM user_type_permission
                WHERE user_type = ?
				AND controller = ?
				AND operation = ?';
        $query = $this->db->query($sql, array($user_type, $controller, $operation));
		return count($query->result_array()) == 1;
	}	
}

?>