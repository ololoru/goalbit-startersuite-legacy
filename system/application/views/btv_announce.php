<?php
/*****************************************************************************
 * btv_announce.php : 
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

$peers_count = 10*count($peer_list);
$peers_line  = '';

foreach ( $peer_list as $peer_info )
{
    $ip_arr = explode('.', $peer_info['ip']);
    $peers_line .= pack("CCCCnI", $ip_arr[0], $ip_arr[1], $ip_arr[2], $ip_arr[3], $peer_info['port'], $peer_info['score']);
}

$port_check_line = '';
if ( isset($port_check) && $port_check !== false )
    $port_check_line = '10:port_checki'. $port_check .'e';

?>
d9:peer_typei<?= $peer_type ?>e6:offseti<?= $offset ?>e7:max_abii<?= $max_abi ?>e15:broadcaster_numi<?= $broadcaster_num ?>e13:superpeer_numi<?= $superpeer_num ?>e8:peer_numi<?= $peer_num ?>e8:intervali<?= $interval ?>e<?= $port_check_line ?>5:peers<?= $peers_count ?>:<?= $peers_line ?>e