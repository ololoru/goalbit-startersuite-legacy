<?php
/*****************************************************************************
 * btv_stats_xml_mode.php : 
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

$peer_types_text[BROADCASTER_PEER]       = 'Brocaster-Peer';
$peer_types_text[SUPER_PEER]             = 'Super-Peer';
$peer_types_text[NORMAL_PEER]            = 'Peer';
$peer_types_text[BROADCASTER_SUPER_PEER] = 'Broad-Super-Peer';

$peer_subtypes_text[PURE_PEER]    = '';
$peer_subtypes_text[MONITOR_PEER] = '( Monitor )';
$peer_subtypes_text[PUMPER_PEER]  = '( Pumper )';

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<channel_list size=\"".count($stats)."\" version=\"1\" >\n";

foreach ( $stats as $channel_id => $peer_list )
{
    echo "\t<channel hash='".$channel_id."'>\n";
    echo "\t\t<peer_list>\n";
    foreach ( $peer_list as $index => $peer_info ) {
        echo "\t\t\t<peer>\n";
        echo "\t\t\t\t<type>".$peer_types_text[$peer_info['type']]." ".$peer_subtypes_text[$peer_info['subtype']]."</type>\n";
        echo "\t\t\t\t<ip>".$peer_info['ip']."</ip>\n";
        echo "\t\t\t\t<port>".$peer_info['port']." (".$peer_info['opened_port'].")</port>\n";
        echo "\t\t\t\t<abi>".$peer_info['abi']."</abi>\n";
		
        if ( $peer_info['downloaded_bytes'] < pow(1024, 1) )
            $download_str = $peer_info['downloaded_bytes'] .' B';
        elseif ( $peer_info['downloaded_bytes'] < pow(1024, 2) )
            $download_str = sprintf( "%.2f", $peer_info['downloaded_bytes']/pow(1024, 1) ) .' KB';
        elseif ( $peer_info['downloaded_bytes'] < pow(1024, 3) )
            $download_str = sprintf( "%.2f", $peer_info['downloaded_bytes']/pow(1024, 2) ) .' MB';
        else
            $download_str = sprintf( "%.2f", $peer_info['downloaded_bytes']/pow(1024, 3) ) .' GB';

        if ( $peer_info['uploaded_bytes'] < pow(1024, 1) )
            $uploaded_str = $peer_info['uploaded_bytes'] .' B';
        elseif ( $peer_info['uploaded_bytes'] < pow(1024, 2) )
            $uploaded_str = sprintf( "%.2f", $peer_info['uploaded_bytes']/pow(1024, 1) ) .' KB';
        elseif ( $peer_info['uploaded_bytes'] < pow(1024, 3) )
            $uploaded_str = sprintf( "%.2f", $peer_info['uploaded_bytes']/pow(1024, 2) ) .' MB';
        else
            $uploaded_str = sprintf( "%.2f", $peer_info['uploaded_bytes']/pow(1024, 3) ) .' GB';
			
		echo "\t\t\t\t<totaldown>".$download_str."</totaldown>\n";
		echo "\t\t\t\t<downrate>".$peer_info['download_rate']." Kbps</downrate>\n";
		echo "\t\t\t\t<totalup>".$uploaded_str."</totalup>\n";
		echo "\t\t\t\t<uprate>".$peer_info['upload_rate']." Kbps</uprate>\n";
		
        echo "\t\t\t\t<qoe>".$peer_info['qoe']."</qoe>\n";
        echo "\t\t\t\t<regdate>".$peer_info['regdate']."</regdate>\n";
        echo "\t\t\t\t<last_report>".$peer_info['last_report']."</last_report>\n";
        echo "\t\t\t</peer>\n";
    }
    echo "\t\t</peer_list>\n";
    echo "\t</channel>\n";
}
echo "</channel_list>\n"; 
?>