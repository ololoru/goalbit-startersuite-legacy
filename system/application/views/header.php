<?php
/*****************************************************************************
 * header.php : 
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="es"> 
<head> 
	<title>Goalbit Starter Suite</title> 
	<link rel="shortcut icon" href="<?= base_url(); ?>images/favicon.ico" type="image/x-icon" /> 
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/style.css"/>
	<!--link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/jquery-ui.css" media="all" /!--> 
	
	<script type="text/javascript" src="<?= base_url(); ?>js/jquery/jquery-1.4.4.min.js"></script> 
	<script type="text/javascript" src="<?= base_url(); ?>js/jquery/jquery-ui-1.8.min.js"></script> 
	<script type="text/javascript" src="<?= base_url(); ?>js/jquery/jqModal.js"></script>
	<script type="text/javascript" src="<?= base_url(); ?>js/utilities.js"></script>

    <? if(isset ($jss))
        foreach($jss as $js)
            echo "\t<script type=\"text/javascript\" src=\"" . base_url() . "js/" . $js . "\"></script>\n";
       if(isset ($csss))
        foreach($csss as $css)
            echo "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . base_url() . "css/" . $css . "\"/>\n";
    ?>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<meta name="robots" content="noindex, nofollow"/>
</head> 

<body>
<? if ((!isset($hide_tabs) or !$hide_tabs) and !$this->session->userdata('news_closed')) { ?>
	<script>
		var last_news = '';
		function hide_news() 
		{
			$.get('<?= base_url() ?>login/news_close/');
			$("#news_text").css('visibility', 'hidden');
			$("#news_buttons").css('visibility', 'hidden');
			$("#news").animate({ height: "0px" }, "fast", function () { $("#news").css('visibility', 'hidden'); jQuery("body").animate({ borderWidth: "5px" }, "fast" ); }  );
		};
		
		function next_news()
		{
			$.get('<?= base_url() ?>channel_list/news/' + last_news,  function(data) { 
				if (data != '') 
				{ 
					$("#news_text").html(data.text); 
					last_news = data.id; 
					if (data.remaining == 0)
						$("#news_button_next").css('visibility', 'hidden');
					else
						$("#news_button_next").css('visibility', 'visible');
				}  
			} );
		}
	</script>

	<div id="news">
		<span id="news_text" ></span>
		<span id="news_buttons" style="float: right; margin-right: 10px">
			<a id="news_button_next" onclick="next_news()" class="button_news"><?= $this->lang->line('Next') ?></a>
			<a class='button_news' onclick="hide_news()" style="margin-left: 10px; "><?= $this->lang->line('Close') ?></a>
		</span>
		<script>
			$.get('<?= base_url() ?>channel_list/news/' + last_news,  function(data) { 
				if (data != '') 
				{ 
					$("#news_text").html(data.text); 
					last_news = data.id; 
					$("#news").css('height', '20px'); 
					jQuery("body").css('border-width', '0px');
					if (data.remaining == 0)
						$("#news_button_next").css('visibility', 'hidden');
					else
						$("#news_button_next").css('visibility', 'visible');
				} 
				else 
				{
					$("#news_text").css('visibility', 'hidden'); 
					$("#news_buttons").css('visibility', 'hidden'); 
				}  
			} );
		</script>
	</div>
<? }?>
	<div class="jqmWindow" id="div_modal">    
	</div>
	
	<div id="tabsycontent">
		<table class="width100">
			<tr>
				<td id="logo">
					<a href="<?= base_url() ?>" class="noborder"><img class="noborder" alt="" src="<?= base_url() ?>images/goalbit-logo.png"/></a>
				</td>
				<td>
					<span id="goal">Goal</span>
					<span id="bit">Bit</span>
				</td>
				<td id="tabs">
					<? if (!isset($hide_tabs) or !$hide_tabs) { ?>
						<a class="tab <? if ($tab == 'channel_list') echo  "selected"; ?>" href="<?= base_url() . "channels" ?>"><?= $this->lang->line('Channels') ?></a>
						<a class="tab <? if ($tab == 'viewers') echo  "selected"; ?>" href="<?= base_url() . "viewers" ?>"><?= $this->lang->line('Viewers') ?></a>
						<a class="tab" onclick="set_modal('<?= base_url() ?>login/change_settings/', false, true); return false;" href="#"><?= $this->lang->line('Settings') ?></a>
						<a class="tab"  href="<?= base_url() ?>login/log_me_out/"><?= $this->lang->line('Log out') ?></a>
					<? } ?>
				</td>
			</tr>
		</table>
		<div id="content">