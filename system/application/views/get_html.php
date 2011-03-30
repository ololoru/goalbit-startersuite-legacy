<?php
/*****************************************************************************
 * get_html.php : 
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
<? $data['hide_tabs'] = true; ?>
<? $this->load->view('header', $data); ?>
		<div style="width: 100%; text-align: center" >
			<p id="title_channel" class="title" style="margin-right: auto; margin-left: auto;"><?= $channel[0]['hosted_channel_name'] ?></p>
		</div>
		<br/><br/>
		
		
		
		
		<script src='<?= base_url() ?>channel_list/get_js/goalbit.js'></script>
		<div style="width: 430px; margin-right: auto; margin-left: auto;" id='goalbit1'
			url='<?= base_url() ?>channel_list/get_goalbit_file/<?= $channel[0]['hosted_channel_id'] ?>'
			width='430px'
			height='390px'
			autoplay='false'
			fullscreen='false'
			aspectRatio=''
			minimumAcceptVersion='<?= urlencode(WEBPLUGINS_VERSION) ?>'
			maximumAcceptVersion='<?= urlencode(WEBPLUGINS_VERSION) ?>'
		</div>
		<script>window.onLoad = loadGoalBitPlayer('goalbit1');</script>
		
		
		
		
		
		<script>
			$('#content').css('min-width', $('#goalbit1').width());
			$('#tabsycontent').css('min-width', $('#goalbit1').width());
		</script>
<? $this->load->view('footer'); ?>