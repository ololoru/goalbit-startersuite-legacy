<?php
/*****************************************************************************
 * btv_calc_stats.php : 
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
<?

$peer_types_text[BROADCASTER_PEER]       = 'Brocaster-Peer';
$peer_types_text[SUPER_PEER]             = 'Super-Peer';
$peer_types_text[NORMAL_PEER]            = 'Peer';
$peer_types_text[BROADCASTER_SUPER_PEER] = 'Broad-Super-Peer';

$peer_subtypes_text[PURE_PEER]    = '';
$peer_subtypes_text[MONITOR_PEER] = '( Monitor )';
$peer_subtypes_text[PUMPER_PEER]  = '( Pumper )';

function get_streaming_stats($peer_list)
{
	
    $total_dw_sp_peers = 0;
    $total_sp_peers    = 0;
    $total_up_peers    = 0;
    $total_peers       = 0;
    $opened_port_peers = 0;
    $streaming_bitrate = STREAM_BITRATE;
    $total_bw_save     = 0;
	$total_broadcaster_peers = 0;

    foreach ( $peer_list as $peer_info )
    {
		if ( $peer_info['type'] == BROADCASTER_PEER or $peer_info['type'] == BROADCASTER_SUPER_PEER)
			$total_broadcaster_peers++;
        else if ( $peer_info['type'] == SUPER_PEER )
        {
            $total_dw_sp_peers += $peer_info['download_rate'];
            $total_sp_peers ++;
        }
        elseif ( $peer_info['type'] == NORMAL_PEER )
        {
            $total_up_peers += $peer_info['upload_rate'];
            $total_peers ++;

            if ( $peer_info['opened_port'] )
                $opened_port_peers ++;
        }
    }

    if ( $total_sp_peers )
        $streaming_bitrate = $total_dw_sp_peers / $total_sp_peers;

    if ( $total_peers )
    {
        $total_dw_bw = $total_peers * $streaming_bitrate;
        $total_bw_save = ( 100 * $total_up_peers ) / $total_dw_bw;
    }

	if ($total_peers != 0)
		$result['opened_port_peers'] = (100 * $opened_port_peers ) / $total_peers;
	else
		$result['opened_port_peers'] = 0;
    $result['streaming_bitrate'] = $streaming_bitrate;
    $result['total_bw_save']     = $total_bw_save;
    $result['total_peers']       = $total_peers;
    $result['total_sp_peers']    = $total_sp_peers;
	$result['total_broadcaster_peers']       = $total_broadcaster_peers;

    return $result;
}
?>