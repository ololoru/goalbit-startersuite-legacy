<?php
/*****************************************************************************
 * goalbit_file.php : 
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
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<channel_list size="1" active_channel="<?= $channel[0]['hosted_channel_id'] ?>"  version="1">
	<channel id="<?= $channel[0]['hosted_channel_id'] ?>">
		<chunk_size><?= $channel[0]['hosted_channel_chunk_size'] ?></chunk_size>
		<tracker_url><?= $channel[0]['hosted_channel_tracker_url'] ?></tracker_url>
		<bitrate><?= $channel[0]['hosted_channel_bitrate'] ?></bitrate>
		<name><?= $channel[0]['hosted_channel_name'] ?></name>
		<description></description>
		<thumb></thumb>
	</channel>
</channel_list>
