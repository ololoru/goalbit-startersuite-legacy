<?php
/*****************************************************************************
 * login.php : 
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
<? $this->load->view('header'); ?>
<p style="text-align: center">
For more information on GoalBit, its products and usage please visit the <a target="_blank" href="http://sourceforge.net/apps/mediawiki/goalbit/index.php?title=Main_Page">Wiki page</a>.
<br/>
To get in touch with other members of the community visit the <a target="_blank" href="http://sourceforge.net/apps/phpbb/goalbit/">Forum</a>.

</p>
<table style="width: 100%; text-align: center">
	<tr>
		<td style="text-align: center">
			<? if ($this->session->userdata('user') == '') { ?>
				<br/>
				<form action="<?= base_url() ?>login/log_me_in/" method="post" name="login" style="text-align: center">
					<table style="margin-left: auto; margin-right: auto;">
						<tr>
							<td class="label"><?= $this->lang->line('User') ?></td>
							<td>
								<input id="user" class="input" type="text" name="user" maxlength="12" value="guest"/>
							</td>
						</tr>
						<tr>
							<td class="label" ><?= $this->lang->line('Password') ?></td>
							<td>
								<input id="password" class="input" type="password" name="password" maxlength="12" value="guest" />
							</td>
						</tr>
						<tr>
							<td colspan=2 style="text-align: right;">
								<? if ($message != '') { ?>
									<p class="error_message"><?= $message ?></p>
								<? } ?>
								<input type="submit" name="LoginSubmit" value ="<?= $this->lang->line('Log in') ?>" class="button" />
							</td>
						</tr>
				</table>
				</form>
			<? } ?>
		</td>
	</tr>
</table>
		
<? $this->load->view('footer'); ?>
