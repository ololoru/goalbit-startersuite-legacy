<?php
/*****************************************************************************
 * viewers.php : 
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
<? include 'btv_calc_stats.php'; ?>

<? if (!(isset($is_refresh) and $is_refresh)) { ?>
	<? $data['tab'] = $tab; ?>
	<? $this->load->view('header', $data); ?>
	<script> 	
		function scanTarget(portlinkid, target, port) 
		{
			document.getElementById(portlinkid).innerHTML = '<?= $this->lang->line('Wait...') ?>';
			$.get('<?= base_url() ?>channel_list/scan_port/' + target + '/' + port,
				function(data)
				{
					if (data != "")
						document.getElementById(portlinkid).innerHTML = data;
				}
			);
		};	
		
		function viewers_refresh()
		{
			
			$.get('<?= base_url() ?>channel_list/viewers_refresh', 
			function(data) {
				if (data.startsWith('ok ')) 
					$('#replace_ajax').html(data.substring(3)); 
				else
					window.location = '<?= base_url() ?>';
			} );
			setTimeout(viewers_refresh, <?= REFRESH_TIME_VIEWERS ?>);
		}
		setTimeout(viewers_refresh, <?= REFRESH_TIME_VIEWERS ?>);
	</script>
<? } ?>


<div id="replace_ajax">
	<br />
	<table cellspacing="0" cellpadding="2" class="channel_list" id="tracker_list">
		<thead>
				<th class="channel_list"><?= $this->lang->line('IP') ?></th>
				<th class="channel_list"><?= $this->lang->line('Port') ?></th>
				<th class="channel_list"><?= $this->lang->line('Type') ?></th>
				<th class="channel_list"><?= $this->lang->line('Subtype') ?></th>
				<th class="channel_list"><?= $this->lang->line('ABI') ?></th>
				<th class="channel_list"><?= $this->lang->line('Port opened?') ?></th>
				<th class="channel_list"><?= $this->lang->line('Downloaded bytes') ?></th>
				<th class="channel_list"><?= $this->lang->line('Download rate') ?></th>
				<th class="channel_list"><?= $this->lang->line('Uploaded bytes') ?></th>
				<th class="channel_list"><?= $this->lang->line('Upload rate') ?></th>
				<th class="channel_list"><?= $this->lang->line('Registration date') ?></th>
				<th class="channel_list"><?= $this->lang->line('Last report') ?></th>
				<th class="channel_list"><?= $this->lang->line('Visibility') ?></th>
		</thead>
		<tbody>
			<? foreach ($tracker_stats as $index => $channel_stats ) { ?>
				<tr>
					<td colspan=13 class="label" style="padding: 10px 10px 10px 10px;" >
						<? if (!isset($channel_ids[$index])) { ?>
							<?= $index ?>
						<? } else { ?>
							<a href="#" class="operations" id="mirar" onclick="set_modal('<?= base_url() ?>channel_list/iframe_code/<?= $channel_ids[$index]['id'] ?>', false, true); setTimeout(viewers_refresh, 10000); return false;">
								<img class="noborder chanel_list_icon" style="vertical-align: middle; padding-right: 10px;" src="<? if (isset($channel_ids[$index]['thumb'])) echo $channel_ids[$index]['thumb']; else echo base_url().'images/goalbit-logo.png'; ?>" />
								<?= $channel_ids[$index]['name'] ?>
							&nbsp;&nbsp;
							</a>
						<? } ?>
						<? $streaming_stats = get_streaming_stats($channel_stats); ?>
						<?= $this->lang->line('Estimated Streaming Bitrate:') ?>
						<? printf( "%.1f", $streaming_stats['streaming_bitrate'] ); ?> <?= $this->lang->line('Kbps') ?>&nbsp;&nbsp;
						<?= $this->lang->line('Peers connected:') ?>
						<?= $streaming_stats['total_peers'] ?>&nbsp;&nbsp;
                        
                        <? if (isset($streaming_stats['total_sp_peers']) and $streaming_stats['total_sp_peers'] != 0) { ?>
                            <?= 'Super-'.$this->lang->line('Peers connected:') ?>
                            <?= $streaming_stats['total_sp_peers'] ?>&nbsp;&nbsp;
                        <? } ?>
                        
						<?= $this->lang->line('Opened Ports:') ?>
						<? printf( "%.1f", $streaming_stats['opened_port_peers'] ); ?> %&nbsp;&nbsp;
						<?= $this->lang->line('Bandwidth Save:') ?>
						<? printf( "%.1f", $streaming_stats['total_bw_save'] ); ?> %&nbsp;&nbsp;
					</td>
				</tr>
				<? foreach ($channel_stats as $index2 => $peer) { ?>
					<tr>
						<td class="label"><? if (($this->session->userdata('user_type') == 'admin') or trim($peer['ip']) == trim($_SERVER['REMOTE_ADDR'])) echo $peer['ip']; else echo 'x.x.x.x'; ?></td>
						<td class="label"><? if (($this->session->userdata('user_type') == 'admin') or trim($peer['ip']) == trim($_SERVER['REMOTE_ADDR'])) echo $peer['port']; else echo 'x'; ?></td>
						<td class="label"><?= $peer_types_text[$peer['type']] ?></td>
						<td class="label"><?= $peer_subtypes_text[$peer['subtype']] ?></td>
						<td class="label"><?= $peer['abi'] ?></td>
						<td class="label"><? if ($peer['opened_port'] > 0) echo $this->lang->line('Yes'); else echo $this->lang->line('No'); ?></td>
						<td class="label"><?= number_format($peer['downloaded_bytes']/1024, 0, ',', '.') ?> MB</td>
						<td class="label"><?= $peer['download_rate'] ?></td>
						<td class="label"><?= number_format($peer['uploaded_bytes']/1024, 0, ',', '.') ?> MB</td>
						<td class="label"><?= $peer['upload_rate'] ?></td>
						<td class="label"><?= $peer['regdate'] ?></td>
						<td class="label"><?= $peer['last_report'] ?></td>
						<td style="padding-left: 10px">
						<a id ="port<?= $index ?>-<?= $index2 ?>" class="operations" href='#' onclick="scanTarget('port<?= $index ?>-<?= $index2 ?>', '<?= $peer['ip'] ?>', '<?= $peer['port'] ?>')" ><?= $this->lang->line('Check port!') ?></a>
						</td>
					</tr>
				<? } ?>
			<? } ?>
		</tbody>
	</table>
	<script>
		$('#content').css('min-width', $('#tracker_list').width());
		$('#tabsycontent').css('min-width', $('#tracker_list').width());
	</script>
</div>
		
<? if (!(isset($is_refresh) and $is_refresh)) { ?>		
	<? $this->load->view('footer'); ?>
<? } ?>