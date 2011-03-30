<?php
/*****************************************************************************
 * btv_stats_default_mode.php : 
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
<html>
<head>
<title>GoalBit Tracker Stats</title>
<style>
body {
color:#555555;
font-family:verdana,arial,helvetica,sans-serif;
font-size:small;
line-height:180%;
}
.sep {
border-bottom: 1px solid #6B86FF;
width: 900px;
line-height:180%;
}
table tr td{
font-size:small;
text-align: left;
}
</style>
</head>
<body>
<div class="sep" style="color:#6B86FF;"><b>GoalBit Tracker Stats</b></div><br />
<table cellspacing="1" cellpadding="1" width="150" >
<tr>
    <td>Channels:</td>
    <td><?= $stats['channel_num'] ?></td>
</tr>
<tr>
    <td>Broadcaster-Peers:</td>
    <td><?= ( isset($stats['broadcaster_num']) ) ? $stats['broadcaster_num'] : 0 ?></td>
</tr>
<tr>
    <td>Super-Peers:</td>
    <td><?= ( isset($stats['superpeer_num']) ) ? $stats['superpeer_num'] : 0 ?></td>
</tr>
<tr>
    <td>Peers:</td>
    <td><?= ( isset($stats['peer_num']) ) ? $stats['peer_num'] : 0 ?></td>
</tr>
</table>
</body>
</html>