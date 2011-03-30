<?php
/*****************************************************************************
 * change_settings.php : 
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
<? if ($first) { ?>
	<div id="content_modal">
    <script> 
		function confirmar()
		{   
			$.post('<?= base_url(); ?>login/change_settings_2/', 
				{
					LastP:    document.getElementById('LastP').value, 
					NewP:     document.getElementById('NewP').value, 
					NewP2:    document.getElementById('NewP2').value,
					language: document.getElementById('language').value,
					newsClosed: document.getElementById('news_closed1').checked
				},
				function(data)
				{
					$("#replacepost").html(data);
				}
			);
		};
	</script>

	<table class="modal_header">
		<tr>
			<td class="modal_title"><?= $this->lang->line('Change settings') ?></td>
            <td class="modal_close"><a class="modal_close" href="" id="closeModal"><?= $this->lang->line('Close') ?></a></td>
		</tr>
		<tr>
			<td colspan=2>
				<br/>
<? } ?>
				<? if (($first) or count($error) != 0) { ?>
					<div id="replacepost" >   
						<table class="width100">
							<tr id ="filaPasswordAnterior">
								<td class="label" id = "current"><?= $this->lang->line('Current password') ?></td>
								<td style="text-align: right">
									<input class="input" type="password"  id="LastP" maxlength=12 value=""/>
									<p class="error_message"><?= $error['LastP']; ?></p>
								</td>
							</tr>
							<tr id ="filaPasswordNuevo">
								<td class="label" id = "new"><?= $this->lang->line('New password') ?></td>
								<td style="text-align: right">
									<input class="input" type="password"  id="NewP" maxlength=12 value=""/>
									<p class="error_message"><?= $error['NewP']; ?></p>
								</td>
							</tr>
							<tr id ="filaPasswordNuevo2">
								<td class="label" id = "new2"><?= $this->lang->line('Confirm new password') ?></td>
								<td style="text-align: right">
									<input class="input" type="password"  id="NewP2" maxlength=12 value=""/>
									<p class="error_message"><?= $error['NewP2']; ?></p>
								</td>
							</tr>
							<tr>
								<td class="label"><?= $this->lang->line('Language') ?></td>
								<td style="text-align: right">
									<select class="input" name="menu" id="language">
										<option value="english" <? if ($user[0]['language'] == 'english') echo 'selected'; ?> ><?= $this->lang->line('English') ?></option>
										<option value="spanish" <? if ($user[0]['language'] == 'spanish') echo 'selected'; ?>><?= $this->lang->line('Spanish') ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="label"><?= $this->lang->line('News') ?></td>
								<td style="text-align: right">
									<span class="label">
									<input type="radio" name="news_closed" id="news_closed0" value="0" <? if (!$user[0]['news_closed']) echo 'checked'; ?>><?= $this->lang->line('Yes!') ?>
									<input type="radio" name="news_closed" id="news_closed1" value="1" <? if ($user[0]['news_closed']) echo 'checked'; ?>><?= $this->lang->line('No, thanks') ?>
									</span>
								</td>
							</tr>
							<tr>
								<td></td>
								<td style="text-align: right">
									<input class="button" type="submit" value ="<?= $this->lang->line('Change') ?>" onclick="confirmar()" />                  
								</td>
							</tr>
						</table>
					</div>
				<? } else { ?>
					<div style="margin-left: auto; margin-right: auto; text-align: center">
						<br/>
						<p class="label" style="text-align: center"><?= $this->lang->line('Settings changed!') ?></p>
					</div>
				<? } ?>
<? if ($first) { ?>
			</td>
		</tr>
	</table>
	</div>
<? } ?>
