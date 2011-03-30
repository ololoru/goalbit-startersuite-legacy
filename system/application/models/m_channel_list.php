<?php
/*****************************************************************************
 * m_channel_list.php : 
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

class M_channel_list extends Model {


	function M_channel_list()
	{
        // Call the Model constructor
		parent::Model();
	}	
	
	function exists($channel)
	{
		$sql = 'SELECT hosted_channel_id FROM hosted_channel WHERE hosted_channel_id = ?';
		$query = $this->db->query($sql, array($channel['hosted_channel_id']));
		return (count($query->result_array()) > 0);
	}
	
	function add_or_replace($channel)
	{
		if ($this->exists($channel))
		{
			$sql = 'UPDATE hosted_channel SET hosted_channel_name = ?, hosted_channel_tracker_url = ?, hosted_channel_bitrate = ?, hosted_channel_chunk_size = ?, hosted_channel_thumb = ?, hosted_channel_del_token =? WHERE hosted_channel_id = ? AND hosted_channel_broadcaster_ip = ?';
			$this->db->query($sql, array($channel['hosted_channel_name'], $channel['hosted_channel_tracker_url'], $channel['hosted_channel_bitrate'], $channel['hosted_channel_chunk_size'], $channel['hosted_channel_thumb'], $channel['hosted_channel_del_token'], $channel['hosted_channel_id'], $channel['hosted_channel_broadcaster_ip']));
		}
		else
		{
			$sql = 'INSERT INTO hosted_channel (hosted_channel_id, hosted_channel_name, hosted_channel_tracker_url, hosted_channel_bitrate, hosted_channel_chunk_size, hosted_channel_broadcaster_ip, hosted_channel_thumb, hosted_channel_del_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
			$this->db->query($sql, array($channel['hosted_channel_id'], $channel['hosted_channel_name'], $channel['hosted_channel_tracker_url'], $channel['hosted_channel_bitrate'], $channel['hosted_channel_chunk_size'], $channel['hosted_channel_broadcaster_ip'], $channel['hosted_channel_thumb'], $channel['hosted_channel_del_token']));
		}
	}

	function get_list()
	{
		$sql = 'SELECT * FROM hosted_channel';
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function delete($channel)
	{
		$sql = 'DELETE FROM hosted_channel WHERE hosted_channel_id = ?';
		$query = $this->db->query($sql, array($channel['hosted_channel_id']));
	}
	
	function delete_web_broadcast($channel)
	{
		$sql = 'DELETE FROM hosted_channel WHERE hosted_channel_id = ? AND hosted_channel_del_token = ?';
		$query = $this->db->query($sql, array($channel['hosted_channel_id'], $channel['hosted_channel_del_token']));
	}
	
	function get($hosted_channel_id)
	{
		$sql = 'SELECT * FROM hosted_channel WHERE hosted_channel_id = ?';
		$query = $this->db->query($sql, array($hosted_channel_id));
		return $query->result_array();
	}
	
	function get_next_news($news_language, $news_id)
	{
		$sql = 'SELECT news_id, news_text 
				FROM news 
				WHERE news_language = ?
				AND news_id != ?
				ORDER BY RAND()';
		$query = $this->db->query($sql, array($news_language, $news_id));
		return $query->result_array();
	}
}

?>