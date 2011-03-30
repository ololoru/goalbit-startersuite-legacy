<?php
/*****************************************************************************
 * installer.php : 
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

<div id="login">
	<p class="title"><?= $this->lang->line('Install Goalbit Starter Suite') ?></p>
		<div style="padding-left: 10px">
			<br/>
			<? if ($installed) {?>
				<p class="label"><?= $this->lang->line('Successfully installed!') ?> <br/>
				<?= $this->lang->line('Acces the aplication with User=admin, Password=adminadmin. CHANGE IT AFTER LOGGING IN') ?></p>
				<br/>
				<a class="operations" href="<?= base_url() ?>"><?= $this->lang->line('Go to the main page') ?></a>
			<? } else { ?>
				<p class="label"><?= $this->lang->line('Welcome to the web installer.') ?> <br/>
				<?= $this->lang->line('We just need to create the program\'s database and tables.') ?><br/>
				<?= $this->lang->line('Would you bee so kind in providing a MySQL URL and credentials capable of doing such operations?') ?></p>
				<br/>
				<form action="<?= base_url() ?>installer/install/" method="post" name="install">
				<table>
					<? if ($message) { ?>
						<tr>
							<td colspan=2 class="label">
								<p class="error_message"><?= $message ?></p>
								<br/>
							</td>
						</tr>
					<? } ?>
					<tr>
						<td class="label" style="padding-right: 5px"><?= $this->lang->line('Database URL') ?></td>
						<td><input id="url" class="input" type="text" name="url" maxlength="200" style="width: 295px;" value="localhost:3306"/></td>
					</tr>
					<tr>
						<td class="label"><?= $this->lang->line('User') ?></td>
						<td><input id="user" class="input" type="text" name="user" maxlength="20" style="width: 150px;" /></td>
					</tr>
					<tr>
						<td class="label"><?= $this->lang->line('Password') ?></td><td><input id="password" class="input" type="password" name="password" maxlength="20" style="width: 150px;"/></td>
					</tr>
					<tr>
						<td colspan=2 style="text-align: right" ><input type="submit" class="button" name="InstallSubmit" value = "<?= $this->lang->line('Install') ?>"/></td>
					</tr>
				</table>
				</form>
				<br />
			<? } ?>
		</div>
	</p>
</div>
    	
<? $this->load->view('footer'); ?>