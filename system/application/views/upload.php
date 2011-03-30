<?php
/*****************************************************************************
 * upload.php : 
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
		<td class="modal_title"><?= $this->lang->line('Upload a GoalBit file') ?></td>
		<td class="modal_close"><a class="modal_close" href="" id="closeModal"><?= $this->lang->line('Close') ?></a></td>
	</tr>
	<tr>
		<td colspan=2>
			<br/>
			<p class="label"><?= $this->lang->line('Use this when the broadcaster is already running and you have the .goalbit file.') ?></p>
			<br/>
			<form action="<?= base_url() ?>channel_list/upload_file" method="post" enctype="multipart/form-data" id="myForm" >
				<table>	
					<tr>
						<td class="label" >
							<input class="input" style="height:20px; width: 500px" type="file" name="file" id="file" />
						</td>
						<td>
							<input class="button" type="submit" value ="<?= $this->lang->line('Submit') ?>"  />					
						</td>
					</tr>
				</table>
			</form>	
		</td>
	</tr>
</table>
