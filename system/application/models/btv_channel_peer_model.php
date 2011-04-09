<?php
/*****************************************************************************
 * btv_channel_peer_model.php : 
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

class Btv_channel_peer_model extends Model {


	function Btv_channel_peer_model()
	{
        // Call the Model constructor
		parent::Model();
	}	
	

    function update_peer_info( $peer_info )
    {
        $opened_port     = false;
        $opened_port_sql = '';

        if ( $peer_info['port_check'] )
        {
            $CI =& get_instance();
            $CI->load->library('Utils');
			
            if ( $CI->utils->check_peer_port( $peer_info ) )
                $opened_port = 1;
            else
                $opened_port = 0;

            $opened_port_sql = ', opened_port = '. $opened_port;
        }

        $sql = 'UPDATE btv_channel_peer
                SET port             = ?,
                    abi              = ?,
                    download_rate    = ( ('. $peer_info['downloaded'] .' - downloaded_bytes )*8/1000 ) / TIMESTAMPDIFF( SECOND, last_report, \''.date("Y-m-d H:i:s", time()).'\' ),
                    downloaded_bytes = ?,
                    upload_rate      = ( ('. $peer_info['uploaded'] .' - uploaded_bytes )*8/1000 ) / TIMESTAMPDIFF( SECOND, last_report, \''.date("Y-m-d H:i:s", time()).'\' ),
                    uploaded_bytes   = ?,
                    qoe              = ?,
                    last_report      = \''.date("Y-m-d H:i:s", time()).'\' '.
                    $opened_port_sql
               .' WHERE channel_hash_id = ? AND
                      peer_hash_id    = ?';

        unset($sql_data);
        $sql_data[] = $peer_info['port'];
        $sql_data[] = $peer_info['abi'];
        $sql_data[] = $peer_info['downloaded'];
        $sql_data[] = $peer_info['uploaded'];
        $sql_data[] = $peer_info['qoe'];
        $sql_data[] = $peer_info['info_hash'];
        $sql_data[] = $peer_info['peer_id'];

        $this->db->query($sql, $sql_data);
        if ( $this->db->affected_rows() == 0 )
        {
            // Always check port the first announce.
            if ( empty($opened_port_sql) )
            {
                $CI =& get_instance();
                $CI->load->library('Utils');

                if ( $CI->utils->check_peer_port( $peer_info ) )
                    $opened_port = 1;
                else
                    $opened_port = 0;

                $opened_port_sql = ', opened_port = '. $opened_port;
            }
			
            $sql = 'INSERT INTO btv_channel_peer
                    SET channel_hash_id = ?,
                        peer_hash_id    = ?,
                        ip              = ?,
                        port            = ?,
                        type            = ?,
                        subtype         = ?,
                        abi             = ?,
                        downloaded_bytes = ?,
                        download_rate    = 0,
                        uploaded_bytes   = ?,
                        upload_rate      = 0,
                        qoe             = ?,
                        regdate         = \''.date("Y-m-d H:i:s", time()).'\',
                        last_report     = \''.date("Y-m-d H:i:s", time()).'\''.
                        $opened_port_sql;

            unset($sql_data);
            $sql_data[] = $peer_info['info_hash'];
            $sql_data[] = $peer_info['peer_id'];
            $sql_data[] = $peer_info['ip'];
            $sql_data[] = $peer_info['port'];
            $sql_data[] = $peer_info['peer_type'];
            $sql_data[] = $peer_info['peer_subtype'];
            $sql_data[] = $peer_info['abi'];
            $sql_data[] = $peer_info['downloaded'];
            $sql_data[] = $peer_info['uploaded'];
            $sql_data[] = $peer_info['qoe'];

            $this->db->query($sql, $sql_data);
        }

			return $opened_port;
    }


    function delete_peer_info( $peer_info )
    {
        $sql = 'DELETE FROM btv_channel_peer
                WHERE channel_hash_id = ? AND
                      peer_hash_id    = ?';

        unset($sql_data);
        $sql_data[] = $peer_info['info_hash'];
        $sql_data[] = $peer_info['peer_id'];

        $this->db->query($sql, $sql_data);
    }


    function get_peers_count_by_type( $channel_hash_id )
    {
        $result['broadcaster_num'] = 0;
        $result['superpeer_num']   = 0;
        $result['peer_num']        = 0;

        $sql = 'SELECT type,
                       COUNT(*) AS peer_num
                FROM btv_channel_peer
                WHERE channel_hash_id = ?
                GROUP BY type';

        unset($sql_data);
        $sql_data[] = $channel_hash_id;

        $query = $this->db->query($sql, $sql_data);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                switch ( $row->type )
			{
				case BROADCASTER_PEER:
                        $result['broadcaster_num'] += $row->peer_num;
					break;

				case SUPER_PEER:
                        $result['superpeer_num'] += $row->peer_num;
					break;

				case NORMAL_PEER:
                        $result['peer_num']    += $row->peer_num;
					break;

				case BROADCASTER_SUPER_PEER:
                        $result['broadcaster_num'] += $row->peer_num;
                        $result['superpeer_num']   += $row->peer_num;
					break;
			}
        }
        }

        return $result;
    }


    function get_peer_list( $peer_info, $peers_by_type )
    {
        $peer_list = array();

        // First, we calc how many peer of each type we will return.
        //
        // Peer selection process:
        //    [broadcaster-peer]       => I send none
        //    [broadcaster-super-peer] => I send none
        //    [super-peer]             => I send broadcaster-peers, broadcaster-super-peer and super-peers
        //    [peer]                   => I send broadcaster-super-peer, super-peers and peers
        //
        switch ( $peer_info['peer_type'] )
        {
            case BROADCASTER_PEER:
            case BROADCASTER_SUPER_PEER:
                $seeder_num  = 0;
                $seeder_type = array(BROADCASTER_PEER, BROADCASTER_SUPER_PEER);
                $peer_num    = $peers_by_type['superpeer_num'];
                $peer_type = array(BROADCASTER_SUPER_PEER, SUPER_PEER);
                break;

            case SUPER_PEER:
                $seeder_num  = $peers_by_type['broadcaster_num'];
                $seeder_type = array(BROADCASTER_PEER, BROADCASTER_SUPER_PEER);
                $peer_num    = $peers_by_type['superpeer_num'];
                $peer_type = array(BROADCASTER_SUPER_PEER, SUPER_PEER);
                break;

            case NORMAL_PEER:
                $seeder_num  = $peers_by_type['superpeer_num'];
                $seeder_type = array(BROADCASTER_SUPER_PEER, SUPER_PEER);
                $peer_num    = $peers_by_type['peer_num'];
                $peer_type = array(NORMAL_PEER);
                break;
        }

        $seeder_porc = ($seeder_num * 100)/($seeder_num + $peer_num);
        $seeder_num_return = round( ( $peer_info['numwant'] * $seeder_porc ) / 100 );

        // Always return at least one seeder
        if ( $seeder_num_return < 1 && $seeder_num > 0 )
            $seeder_num_return = 1;
        elseif ( $seeder_num_return > $seeder_num )
            $seeder_num_return = $seeder_num;

        $peer_num_return = $peer_info['numwant'] - $seeder_num_return;
	   
        $sql = '(
                    SELECT ip,
                           port
                    FROM btv_channel_peer
                    WHERE channel_hash_id = ? AND
                          type IN ('.implode(',', $seeder_type).') AND
                          peer_hash_id != ?
                    ORDER BY ABS( CAST(INET_ATON(?) AS SIGNED) - CAST(INET_ATON(ip) AS SIGNED) )*( -LN(1.0-RAND())*1/5 ) ASC '.
                           //| ------------- Distancia entre IPs --------------| |------- Random exp -------|
                   'LIMIT '. $seeder_num_return .'
                )
                UNION
                (
                    SELECT ip,
                           port
                    FROM btv_channel_peer
                    WHERE channel_hash_id = ? AND
                          type IN ('.implode(',', $peer_type).') AND
						  peer_hash_id != ? AND
                          opened_port = 1
                    ORDER BY ABS( CAST(INET_ATON(?) AS SIGNED) - CAST(INET_ATON(ip) AS SIGNED) )*( -LN(1.0-RAND())*1/5 ) ASC '.
                           //| ------------- Distancia entre IPs --------------| |------- Random exp -------|
                   'LIMIT '. $peer_num_return .'
                )';
				
        unset($sql_data);
        $sql_data[] = $peer_info['info_hash'];
        $sql_data[] = $peer_info['peer_id'];
        $sql_data[] = $peer_info['ip'];
        $sql_data[] = $peer_info['info_hash'];
        $sql_data[] = $peer_info['peer_id'];
        $sql_data[] = $peer_info['ip'];

        $query = $this->db->query($sql, $sql_data);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                $peer_info_aux['ip']    = $row->ip;
                $peer_info_aux['port']  = $row->port;
				$peer_info_aux['score'] = 0;

				$peer_list[] = $peer_info_aux;
			}
        }

        return $peer_list;
    }


    function get_max_seeder_abi( $peer_info )
    {
        $max_seeder_abi = 0;

        switch ( $peer_info['peer_type'] )
        {
            case BROADCASTER_PEER:
            case BROADCASTER_SUPER_PEER:
                return $max_seeder_abi;
                break;

            case SUPER_PEER:
                $seeder_type = array(BROADCASTER_PEER, BROADCASTER_SUPER_PEER);
                break;

            case NORMAL_PEER:
                $seeder_type = array(BROADCASTER_SUPER_PEER, SUPER_PEER);
                break;
        }

        $sql = 'SELECT abi,
                       last_report
                FROM btv_channel_peer
                WHERE channel_hash_id = ? AND
                      type IN ('. implode(',', $seeder_type) .') AND
                      abi != '. BT_NULL .'
                ORDER BY abi DESC
                LIMIT 1';

        unset($sql_data);
        $sql_data[] = $peer_info['info_hash'];
		
        $query = $this->db->query($sql, $sql_data);
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row();

            $diff_time      = time() - strtotime($row->last_report);
            $max_seeder_abi = $row->abi + (int)( $diff_time / CHUNK_RATE );
        }
        else
            $max_seeder_abi = BT_NULL;

        return $max_seeder_abi;
    }


    function get_list_stats()
    {
        $stats = array();

        $sql = 'SELECT channel_hash_id,
                       ip,
                       port,
                       type,
                       subtype,
                       abi,
                       opened_port,
                       downloaded_bytes,
                       download_rate,
                       uploaded_bytes,
                       upload_rate,
                       qoe,
                       regdate,
                       last_report
                FROM btv_channel_peer
                ORDER BY channel_hash_id,
                         last_report DESC';

		$query = $this->db->query($sql);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                $peer_info_aux['ip']               = $row->ip;
                $peer_info_aux['port']             = $row->port;
                $peer_info_aux['type']             = $row->type;
                $peer_info_aux['subtype']          = $row->subtype;
                $peer_info_aux['abi']              = $row->abi;
                $peer_info_aux['opened_port']      = $row->opened_port;
                $peer_info_aux['downloaded_bytes'] = $row->downloaded_bytes;
                $peer_info_aux['download_rate']    = $row->download_rate;
                $peer_info_aux['uploaded_bytes']   = $row->uploaded_bytes;
                $peer_info_aux['upload_rate']      = $row->upload_rate;
                $peer_info_aux['qoe']              = $row->qoe;
                $peer_info_aux['regdate']          = $row->regdate;
                $peer_info_aux['last_report']      = $row->last_report;

                $stats[$row->channel_hash_id][] = $peer_info_aux;
            }
        }

        return $stats;
    }


    function get_list_stats_for_channel( $channel_hash_id )
    {
        $stats = array();

        $sql = 'SELECT channel_hash_id,
                       ip,
                       port,
                       type,
                       subtype,
                       abi,
                       opened_port,
                       downloaded_bytes,
                       download_rate,
                       uploaded_bytes,
                       upload_rate,
                       qoe,
                       regdate,
                       last_report
                FROM btv_channel_peer
                WHERE channel_hash_id = ?
                ORDER BY channel_hash_id,
                         last_report DESC';

        $sql_data[] = $channel_hash_id;

        $query = $this->db->query($sql, $sql_data);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                $peer_info_aux['ip']               = $row->ip;
                $peer_info_aux['port']             = $row->port;
                $peer_info_aux['type']             = $row->type;
                $peer_info_aux['subtype']          = $row->subtype;
                $peer_info_aux['abi']              = $row->abi;
                $peer_info_aux['opened_port']      = $row->opened_port;
                $peer_info_aux['downloaded_bytes'] = $row->downloaded_bytes;
                $peer_info_aux['download_rate']    = $row->download_rate;
                $peer_info_aux['uploaded_bytes']   = $row->uploaded_bytes;
                $peer_info_aux['upload_rate']      = $row->upload_rate;
                $peer_info_aux['qoe']              = $row->qoe;
                $peer_info_aux['regdate']          = $row->regdate;
                $peer_info_aux['last_report']      = $row->last_report;

                $stats[$row->channel_hash_id][] = $peer_info_aux;
            }
        }

        return $stats;
    }


    function get_pump_stats()
    {
        $stats = array();

        $sql = 'SELECT channel_hash_id,
                       type,
                       COUNT(*) AS peer_num
                FROM btv_channel_peer
                WHERE type IN ('. BROADCASTER_SUPER_PEER .','. SUPER_PEER .','. NORMAL_PEER .') AND
                      subtype NOT IN ('. PUMPER_PEER .','. MONITOR_PEER .')
                GROUP BY channel_hash_id,
                         type';

		$query = $this->db->query($sql);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                switch ( $row->type )
                {
                    case BROADCASTER_SUPER_PEER:
                        $stats[$row->channel_hash_id]['superpeer_num'] += $row->peer_num;
                        break;

                    case SUPER_PEER:
                        $stats[$row->channel_hash_id]['superpeer_num'] += $row->peer_num;
                        break;

                    case NORMAL_PEER:
                        $stats[$row->channel_hash_id]['peer_num'] += $row->peer_num;
                        break;
                }
            }
        }

        return $stats;
    }


    function get_gall_stats()
    {
        $stats = array();

        $sql = 'SELECT channel_hash_id,
                       type,
                       COUNT(*) AS peer_num
                FROM btv_channel_peer
                WHERE type IN ('. BROADCASTER_SUPER_PEER .','. SUPER_PEER .','. NORMAL_PEER .')
                GROUP BY channel_hash_id,
                         type';

		$query = $this->db->query($sql);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                switch ( $row->type )
                {
                    case BROADCASTER_SUPER_PEER:
                        $stats[$row->channel_hash_id]['superpeer_num'] += $row->peer_num;
                        break;

                    case SUPER_PEER:
                        $stats[$row->channel_hash_id]['superpeer_num'] += $row->peer_num;
                        break;

                    case NORMAL_PEER:
                        $stats[$row->channel_hash_id]['peer_num'] += $row->peer_num;
                        break;
                }
            }
        }

        return $stats;
    }


    function get_default_stats()
    {
        $stats = array();

        $sql = 'SELECT type,
                       COUNT(*) AS peer_num
                FROM btv_channel_peer
                GROUP BY type';

		$query = $this->db->query($sql);
        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                switch ( $row->type )
                {
                    case BROADCASTER_PEER:
                        $stats['broadcaster_num'] += $row->peer_num;
                        break;

                    case SUPER_PEER:
                        $stats['superpeer_num'] += $row->peer_num;
                        break;

                    case NORMAL_PEER:
                        $stats['peer_num']    += $row->peer_num;
                        break;

                    case BROADCASTER_SUPER_PEER:
                        $stats['broadcaster_num'] += $row->peer_num;
                        $stats['superpeer_num']   += $row->peer_num;
                        break;
                }
            }
        }

        $sql = 'SELECT COUNT( DISTINCT channel_hash_id ) AS channel_num
                FROM btv_channel_peer';

		$query = $this->db->query($sql);
        $row   = $query->row();
        $stats['channel_num'] = $row->channel_num;

        return $stats;
    }


    function update_peers()
    {
        $sql = 'DELETE FROM btv_channel_peer
                WHERE ( UNIX_TIMESTAMP(\''.date("Y-m-d H:i:s", time()).'\') - UNIX_TIMESTAMP(last_report) ) > '. PEERS_TIMEOUT;

        $this->db->query($sql);
    }

	function exists_peer($ip, $port)
	{
		$sql = "SELECT COUNT(*) AS 'c' FROM
				btv_channel_peer
				WHERE ip = ?
				AND port = ?";
		$query = $this->db->query($sql, array($ip, $port));
		$row   = $query->row();
		return ($row->c > 0);
	}
}

?>
