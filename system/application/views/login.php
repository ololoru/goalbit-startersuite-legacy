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

<table style="width: 100%; text-align: center">
	<tr>
		<td style="text-align: center">
			<p class="title">How to broadcast</p> 
			<br/>
			<p class="label">To broadcast from you video files or webcam just use the option</p> 
			<img src="<?= base_url() ?>images/broadcast_link.png" style="margin: 5px 5px 5px 5px"/>
			<p class="label">in the "Channels" section.</p> 
			<br/>
			<p class="label">Once you are broadcasting your channel should appear in the listing.</p> 
			<br/>
			<p class="label">Copy the share link and send it to your friends!</p> 
		</td>
	</tr>
	<tr>
		<td style="text-align: center">
			<br/>
			<br/>
			<br/>
			<br/>
			<p class="title">Come on and get in...</p> 
		</td>
	</tr>
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
	<tr>
		<td style="text-align: center">				
			<br/>
			<br/>
			<p class="label" >Comments? Help? Post at our <a class="operaciones" target="_blank" href="http://sourceforge.net/apps/phpbb/goalbit/viewforum.php?f=8">official forum!<a/> </p> 
			<br/>
			<p class="label" style="color: #B0B0B0" >Notice: You should have enough upload bandwidth for broadcasting. We are not providing support for this!</p> 
			<br/>
			<p class="label" style="color: #B0B0B0" >This is a simplified version of our professional solution, for testing purposes. For the professional suite please visit <a class="modal_title" target="_blank" href="http://goalbit-solutions.com">goalbit-solutions.com</a></p> 
		</td>
	</tr>
</table>
		
<? $this->load->view('footer'); ?>
