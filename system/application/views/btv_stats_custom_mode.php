<?php
/*****************************************************************************
 * btv_stats_custom_mode.php : 
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


function get_streaming_stats($peer_list)
{
    $total_dw_sp_peers = 0;
    $total_sp_peers    = 0;
    $total_up_peers    = 0;
    $total_peers       = 0;
    $opened_port_peers = 0;
    $streaming_bitrate = STREAM_BITRATE;
    $total_bw_save     = 0;

    foreach ( $peer_list as $peer_info )
    {
        if ( $peer_info['type'] == SUPER_PEER )
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

    $result['opened_port_peers'] = (100 * $opened_port_peers ) / $total_peers;
    $result['streaming_bitrate'] = $streaming_bitrate;
    $result['total_bw_save']     = $total_bw_save;
    $result['total_sp_peers']    = $total_sp_peers;
    $result['total_peers']       = $total_peers;

    return $result;
}


?>
<htlm>
<head>
<title>GoalBit Tracker Full Stats</title>
<style>
body {
color:#555555;
font-family:verdana,arial,helvetica,sans-serif;
font-size:small;
line-height:180%;
}
.sep {
border-bottom: 1px solid #6B86FF;
width: 1200px;
line-height:180%;
}
table tr th{
font-size:small;
line-height:180%;
text-align: left;
}
table tr td{
font-size:small;
text-align: left;
}
</style>
</head>
<body>
<div class="sep" style="color:#6B86FF;"><b>GoalBit Tracker Full Stats</b></div><br />
<form id="channel_form" action="?" method="post" >
<b>Channel:</b>&nbsp;&nbsp;
<select name="channel_id" onchange="document.getElementById('channel_form').submit()" >
<?php
foreach ( $channel_list as $channel_id => $channel_info )
{
    $select = '';
    if ( $channel_id == $channel_selected_id )
        $select = 'selected="selected"';
?>
    <option value="<?= $channel_id ?>" <?= $select ?>  ><?= $channel_info['certificate_cn'] ?> / <?= $channel_info['xmltv_channel_id'] ?></option>
<?php
}
?>
</select>
<input type="submit">
</form>

<br />
<table cellspacing="1" cellpadding="1" width="1200" >
<?php
foreach ( $stats as $channel_id => $peer_list )
{
    $streaming_stats = get_streaming_stats($peer_list);
?>
    <tr>
        <td colspan="11">
        <table cellspacing="1" cellpadding="1" >
            <tr><td><b>Estimated Streaming Bitrate:</b></td><td><?php printf( "%.1f", $streaming_stats['streaming_bitrate'] ); ?> Kbps</td></tr>
            <tr><td><b>Peers connected:</b></td><td><?= $streaming_stats['total_peers'] ?></td></tr>
            <tr><td><b>Opened Ports:</b></td><td><?php printf( "%.1f", $streaming_stats['opened_port_peers'] ); ?> %</td></tr>
            <tr><td><b>Bandwidth Save:</b></td><td><?php printf( "%.1f", $streaming_stats['total_bw_save'] ); ?> %</td></tr>
        </table>
        </td>
    </tr>
    <tr><td colspan="11">&nbsp;</td></tr>
    <tr>
        <th width="130" >Peer Type</th>
        <th >IP</th>
        <th width="120" >Port&nbsp;&nbsp;(opened?)</th>
        <th>ABI</th>
        <th>Total Down</th>
        <th>Down Rate</th>
        <th>Total UP</th>
        <th>UP Rate</th>
        <th>QoE</th>
        <th>Registered</th>
        <th>Last Report</th>
    </tr>
    <?php
    foreach ( $peer_list as $index => $peer_info )
    {
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
    ?>
    <tr>
        <td><?= $peer_types_text[$peer_info['type']] ?> <?= $peer_subtypes_text[$peer_info['subtype']] ?></td>
        <td><?= $peer_info['ip'] ?></td>
        <td><?= $peer_info['port'] ?>&nbsp;&nbsp;(<?= $peer_info['opened_port'] ?>)</td>
        <td><?= $peer_info['abi'] ?></td>
        <td><?= $download_str ?></td>
        <td><?= $peer_info['download_rate'] ?> Kbps</td>
        <td><?= $uploaded_str ?></td>
        <td><?= $peer_info['upload_rate'] ?> Kbps</td>
        <td><?php printf("%.2f", $peer_info['qoe']) ?></td>
        <td><?= $peer_info['regdate'] ?></td>
        <td><?= $peer_info['last_report'] ?></td>
    </tr>
    <?php
    }
    ?>
    <tr>
        <td colspan="7" >&nbsp;</td>
    </tr>
<?php
}
?>
</table>
</body>
</htlm>