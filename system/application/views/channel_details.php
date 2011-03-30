<?php
/*****************************************************************************
 * channel_details.php : 
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
<table class="modal_header">
	<tr>
		<td class="modal_title"><?= $channel[0]['hosted_channel_name'] ?></td>
		<td class="modal_close"><a class="modal_close" href="" id="closeModal"><?= $this->lang->line('Close') ?></a></td>
	</tr>
	<tr>
		<td colspan=2>
			<br/>
			<table>
				<tr>
					<td class="label" style="padding-right: 15px"><?= $this->lang->line('Id') ?></td>
					<td class="label"><?= $channel[0]['hosted_channel_id'] ?></td>
				</tr>
				<tr>
					<td class="label" style="padding-right: 15px"><?= $this->lang->line('Announce URL') ?></td>
					<td class="label"><?= $channel[0]['hosted_channel_tracker_url'] ?></td>
				</tr>
				<tr>
					<td class="label" style="padding-right: 15px"><?= $this->lang->line('Bitrate') ?></td>
					<td class="label"><?= $channel[0]['hosted_channel_bitrate'] ?></td>
				</tr>
				<tr>
					<td class="label" style="padding-right: 15px"><?= $this->lang->line('Chunk Size') ?></td>
					<td class="label"><?= $channel[0]['hosted_channel_chunk_size'] ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
