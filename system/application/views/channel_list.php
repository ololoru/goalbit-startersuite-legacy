<?php
/*****************************************************************************
 * channel_list.php : 
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
		function delete_channel(channel, name)
		{
			var aca = confirm(<?= $this->lang->line('') ?>'Are you sure you want to delete ' + name + '?');
			if (aca == true)
			{
				$.post('<?= base_url() ?>channel_list/delete/', 
					{
						Id: channel
					},
					function(data)
					{
						if (data != "")
							alert(data);
						else
							$.get('<?= base_url() ?>channel_list/channel_list_refresh', function(data) { $('#replace_ajax').html(data.substring(3)); } );
					}
				);
			}
		}
		
		function refresh_channel_list()
		{
			
			$.get('<?= base_url() ?>channel_list/channel_list_refresh', 
			function(data) { 
				if (data.startsWith('ok ')) 
					$('#replace_ajax').html(data.substring(3)); 
				else
					window.location = '<?= base_url() ?>';
			} );
			setTimeout(refresh_channel_list, <?= REFRESH_TIME ?>);
		}
		setTimeout(refresh_channel_list, <?= REFRESH_TIME ?>);
	</script>
<? } ?>


<div id="replace_ajax">
	<br/>
	<? if (count($channel_list) == 0) { ?>
		<p class="title"><?= $this->lang->line('There are no channels :)') ?></p>
		<br/>
	<? } ?>
	<br/>
	<div class="operations" >
		<a class="operations" href="<?= base_url() ?>broadcaster" target="_blank"><img class="noborder" src="<?= base_url() ?>images/broadcast.png" width="16px" />&nbsp;<?= $this->lang->line('Broadcast now!') ?>&nbsp;&nbsp;</a>
		<a class="operations" href="#" id="add" onclick="set_modal('<?= base_url() ?>channel_list/upload/', true, true)"><img class="noborder" src="<?= base_url() ?>images/submit.png" width="16px" />&nbsp;<?= $this->lang->line('Submit file') ?></a>
		
		<? if (isset($message) and $message != '') { ?>
			&nbsp;&nbsp;<span class="error_message"><?= $this->lang->line('Submitting file:') ?> <?= $message ?></span>
		<? } ?>
	</div>

	<? if (count($channel_list) != 0) { ?>					
		<table cellspacing="0" cellpadding="2" class="channel_list" id="channel_list">
			<thead>
				<tr>
					<th class="channel_list" style="text-align: left; padding-left: 10px"><?= $this->lang->line('Name') ?></th>
					<th class="channel_list" style="text-align: right; padding-right: 15px"><?= $this->lang->line('Details') ?></th>
				</tr>
			</thead>
			<tbody>
			<? foreach ($channel_list as $index => $channel ) { ?>
				<tr>
					<td class="channel_list_icon">
						<a href="#" id="mirar" onclick="set_modal('<?= base_url() ?>channel_list/iframe_code/<?= $channel['hosted_channel_id'] ?>', false, true); return false;">
							<img class="noborder chanel_list_icon" src="<? if ($channel['hosted_channel_thumb'] != '') echo $channel['hosted_channel_thumb']; else echo base_url().'images/goalbit-logo.png'; ?>" title="<?= $this->lang->line('Watch') ?> <?= $channel['hosted_channel_id'] ?>"/>
						</a>
					</td>
					<td class="channel_list_channel_info">
						<a class="operations" href="#" id="mirar" onclick="set_modal('<?= base_url() ?>channel_list/iframe_code/<?= $channel['hosted_channel_id'] ?>', false, true); return false;" title="<?= $this->lang->line('Watch') ?> <?= $channel['hosted_channel_id'] ?>"><?= $channel['hosted_channel_name'] ?></a>
						<div class="chanel_list_channel_details">
							<input type="submit" name="embeb" value = "<?= $this->lang->line('Add it to your site') ?>" class="button" Title="<?= $this->lang->line('Embeb in your site!') ?>" onclick="set_modal('<?= base_url() ?>channel_list/embeb_code/<?= $channel['hosted_channel_id'] ?>', false, false); return false;" />
							&nbsp;
							<input type="submit" name="share" value = "<?= $this->lang->line('Share it!') ?>" class="button" Title="<?= $this->lang->line('Share it with other people') ?>" onclick="window.open('<?= base_url() ?>channel_list/get_html/<?= $channel['hosted_channel_id'] ?>'); return false;" />
							&nbsp;&nbsp;
						
							<? unset($streaming_stats) ?>
							<? if (isset($tracker_stats[$channel_ids[$channel['hosted_channel_id']]])) { ?>
								<? $streaming_stats = get_streaming_stats($tracker_stats[$channel_ids[$channel['hosted_channel_id']]]); ?>
								<? if (!isset($streaming_stats['total_broadcaster_peers']) or $streaming_stats['total_broadcaster_peers'] == 0) { ?>
									<img style="padding-right: 5px" src="<?= base_url() ?>images/error.png" title="<?= $this->lang->line('No broadcasters!') ?>" />
								<? } else { ?>
									<img src="<?= base_url() ?>images/broadcasters.png" width="13px" title="<?= $this->lang->line('Broadcasters') ?>" />
									<?= $streaming_stats['total_broadcaster_peers'] ?>
								<? } ?>
							<? } else { ?>
								<img style="padding-right: 5px" src="<?= base_url() ?>images/error.png" title="<?= $this->lang->line('No broadcasters!') ?>" />
							<? } ?>
							
                            <? if (isset($streaming_stats['total_sp_peers']) and $streaming_stats['total_sp_peers'] != 0) { ?>
                                <img src="<?= base_url() ?>images/superviewers.png" width="13px" title="<?= $this->lang->line('Super peers') ?>" />
                                <? if (isset($streaming_stats['total_sp_peers'])) echo $streaming_stats['total_sp_peers']; else echo '0'; ?>
                            <? } ?>
                            
							<img src="<?= base_url() ?>images/viewers.png" width="13px" title="<?= $this->lang->line('Viewers') ?>" />
							<? if (isset($streaming_stats['total_peers'])) echo $streaming_stats['total_peers']; else echo '0'; ?>

							<a class="noborder" style="padding-left: 7px; padding-right: 7px; " href="#" onclick="set_modal('<?= base_url() ?>channel_list/channel_details/<?= $channel['hosted_channel_id'] ?>', false, true); return false;">
								<img class="noborder" src="<?= base_url() ?>images/view.gif" width="13px" title="<?= $this->lang->line('Details') ?>" />
							</a>
							<a href="#" onclick="delete_channel('<?= $channel['hosted_channel_id'] ?>', '<?= $channel['hosted_channel_name'] ?>')">
								<img class="noborder" src="<?= base_url() ?>images/delete.gif" title="<?= $this->lang->line('Delete') ?>" />
							</a>
						</div>
					</td>
				</tr>
			<? } ?>
			</tbody>
		</table>
	<? } ?>
</div>

<? if (!(isset($is_refresh) and $is_refresh)) { ?>		
	<? $this->load->view('footer'); ?>
<? } ?>