<?php
/*****************************************************************************
 * broadcaster.php : 
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
<?
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Linux') > 0)
{
	$data["jss"] = array('jquery.easing.1.3.js','jqueryFileTree.js');
	$data["csss"] = array('jqueryFileTree.css');
}
$this->load->view('header',$data);
?>


<script>
	var goalbit;
	
	function getGoalbitPlugin(name)
	{
		if (window.document[name])
		{
			return window.document[name];
		}
		if (navigator.appName.indexOf("Microsoft Internet")==-1)
		{
			if (document.embeds && document.embeds[name]){
				return document.embeds[name];
			}
		}
		else
		{
			return document.getElementById(name);
		}
		return null;
	}
	
	function paint_black(a)
	{
		$(a).css('color', '#838383');
	}
	
	function unpaint_black(a)
	{
		$(a).css('color', '#AFAFAF');
	}
	
	
	var MuteState = 0;
	var volValor = 100;
	var volMoving = 0;
	var mostrado = 0;
	var mostrando = 0;
	var guardando = 0;


	function mouseX(evt) {
	if (evt.pageX) return evt.pageX;
	else if (evt.clientX)
	   return evt.clientX + (document.documentElement.scrollLeft ?
	   document.documentElement.scrollLeft :
	   document.body.scrollLeft);
	else return null;
	}

	function mouseY(evt) {
	if (evt.pageY) return evt.pageY;
	else if (evt.clientY)
	   return evt.clientY + (document.documentElement.scrollTop ?
	   document.documentElement.scrollTop :
	   document.body.scrollTop);
	else return null;
	}

	function cursorDentroTodoVol(event)
	{
		var p = $("#VolumeBar");
		var position = p.offset();
		var x = parseInt(mouseX(event)) - parseInt(position.left);
		var y = parseInt(mouseY(event)) - parseInt(position.top);
		
		if (x < 0 || x > 135)
			return false;
		
		if (y < 0 || y > 15)
			return false;
		
		return true;
	}

	function guardar()
	{
		if (volMoving == 1)
			volumebarup();
		guardando = 1;
		document.getElementById("VolumePipe").style.width = "0px";
		document.getElementById("VolumeBar").style.borderRight = "0px solid #CCCCCC";
		$("#VolumeBar").animate( { width:"0px"}, 500, function() 
		{
			document.getElementById("VolumeBar").style.border = "0px solid #CCCCCC";			
			document.getElementById("ButtonMute").style.borderRight = "1px solid #CCCCCC";
			guardando = 0;
			mostrado = 0;
		} );
	}

	function mostrar()
	{
		mostrando = 1;
		document.getElementById("ButtonMute").style.borderRight = "0px solid #CCCCCC";
		document.getElementById("VolumeBar").style.borderTop = "1px solid #CCCCCC";
		document.getElementById("VolumeBar").style.borderBottom = "1px solid #CCCCCC";
		
		$("#VolumeBar").animate( { width:"125px"}, 500, function() { 
			document.getElementById("VolumeBar").style.borderRight = "1px solid #CCCCCC";
			document.getElementById("VolumePipe").style.width = "2px";
			mostrando = 0;
			mostrado = 1;
		}  );
	}


	function volumebarout(event)
	{
		if (cursorDentroTodoVol(event) == false  && mostrado == 1 && mostrando == 0 && guardando == 0)
			guardar();
	}		
			
	function volumebarover(event)
	{
		if (volMoving == 1)
		{
			var p = $("#VolumeBar");
			var position = p.offset();
			var pos = parseInt(mouseX(event) - position.left) - 13;
			
			volValor = pos;
			if (pos < 5)
				volValor = 0;
			if (pos > 97)
				volValor = 100;

			if (pos < 0)
				pos = -7;
			if (pos > 97)
				pos = 100;
			pos = 100 - pos + 13;
			$("#VolumePipe").css('margin-left', "-" + pos + "px");
		}
	}

	function volumebarup()
	{
		if (MuteState == 0)
			goalbit.audio.volume = volValor;
		volMoving = 0;	
	}

	function volumebardown(event) 
	{
		volMoving = 1;
		volumebarover(event);
	}

	function muteClick(event){

		MuteState = (MuteState + 1) % 2;
		goalbit.audio.toggleMute();		

		if (MuteState == 0) 
            document.getElementById("ButtonMute").src = "<?= base_url() ?>images/mute2.png";
		else
            document.getElementById("ButtonMute").src = "<?= base_url() ?>images/mute4.png";
			
		if (MuteState == 0)
			goalbit.audio.volume = volValor;
	}

	function mutemouseover(event) {
		if (MuteState == 0)
            document.getElementById("ButtonMute").src = "<?= base_url() ?>images/mute2.png";
		else
            document.getElementById("ButtonMute").src = "<?= base_url() ?>images/mute4.png";
			
		if (mostrado == 0 && mostrando == 0 && guardando == 0)
			mostrar();
	}

	function mutemouseout(event) {
		if (MuteState == 0)
            document.getElementById("ButtonMute").src = "<?= base_url() ?>images/mute1.png";
		else
            document.getElementById("ButtonMute").src = "<?= base_url() ?>images/mute3.png";
			
		if (cursorDentroTodoVol(event) == false && mostrado == 1 && mostrando == 0 && guardando == 0)
			guardar();
	}


	
	function choose_input()
	{
		goalbit = getGoalbitPlugin("goalbit");
		$("#choose_input").jqm({modal: true}).draggable();
        $("#choose_input").jqmShow();
		$("#choose_input").css('visibility', 'hidden');
		setTimeout(function() { $('#choose_input').css('margin-left', (($('#choose_input').width() + 100)*(-1)/2)); $("#choose_input").css('visibility', 'visible'); }, 250);
	}
	
	var OS_WINDOWS = 0;
	var OS_LINUX = 1;
	var OS = navigator.appVersion.indexOf("Win")!=-1 ? OS_WINDOWS:OS_LINUX;
	var MEDIA_TYPE_FILE     = 0;
	var MEDIA_TYPE_WEBCAM   = 1;
	var MEDIA_TYPE_ADVANCED = 2;
	var id_incrementer = 0;
	var channel_id = '<?= $channel_id ?>';
	var file_path="";
	var file_name="";
	var broadcasting = false;
	var play_list_items = new Array();
	var transcode_ab =32;
	var bitrate = 352;
	var chunk_size = 65536;
	var auto_broad_type = 4;
	var tracker_url = '<?= base_url() ?>btv_tracker/announce';
	var timer;	
	var path = '';
	var slash = OS == OS_WINDOWS ? "\\" : "/";
	var edit_transcode_id = "";
	var TRANSCODE_TYPE_REGULAR  = 0;
	var TRANSCODE_TYPE_GOOD     = 1;
	var TRANSCODE_TYPE_ORIGINAL = 2;
	var TRANSCODE_TYPE_CUSTOM   = 3;
	var del_token = '';
    var play_list_current_index = -1;
	var play_list_current_timeout = -1000;
    var loop = true;
    
	function getDuplicate(vb)
	{
		return ":duplicate{dst=display,dst=qoe_injector_duplicate{dst=std{access=goalbit_broadcaster{piece-len="+chunk_size+",tracker-url='"+tracker_url+"',auto-broad-type="+auto_broad_type+",bitrate="+vb+",channel-name='"+channel_id+"'},mux=asf,dst='"+channel_id+"'}}}";
	}
	
	function getRegularTranscode(ab,vb)
	{
		return "#transcode{vb="+vb+",width=320,venc=x264{subme=5,ref=3,bframes=3,qpmax=28},vcodec=h264,acodec=mp4a,ab="+ab+",threads=1}";
	}
	
	function getGoodTranscode(ab,vb)
	{
		return "#transcode{vb="+vb+",width=640,venc=x264{subme=5,ref=3,bframes=3,qpmax=28},vcodec=h264,acodec=mp4a,ab="+ab+",threads=1}";
	}
	
	function getOriginalTranscode()
	{
		return "#transcode{}";
	}
	
	function addPlaylistItem(){

		var $ul = $("#playlist > ul");

		var item = new Object();
		item.id = id_incrementer++;
		item.type = tmp_media_type;
		item.transcode = getRegularTranscode(transcode_ab,bitrate);
		item.transcode_type = TRANSCODE_TYPE_REGULAR;
		item.duplicate = getDuplicate(bitrate);

		switch(item.type){
			case MEDIA_TYPE_FILE:
				if (OS == OS_WINDOWS)
					item.mrl = file_path;
				else
					item.mrl = "file://" + file_path;
				$("#choose_input").jqmHide();
				item.label = file_name;
                item.type = "file";
				break;
			case MEDIA_TYPE_WEBCAM:
				item.label = "<?= $this->lang->line('Webcam') ?>";
				switch(OS){
					case OS_WINDOWS:
						item.mrl = "dshow://";
						break;
					default:
						item.mrl = "v4l2://";
						break;
				}
                item.type = "webcam";
				$("#choose_input").jqmHide();
				break;
			case MEDIA_TYPE_ADVANCED:
				item.mrl = $("#input_advanced").val();
				$("#choose_advanced").jqmHide();
				item.label = item.mrl;
                item.type = "advanced";
				break;
		}
		play_list_items.push(item);

		var li =
			"<li class='playlist_item' id='list_item_"+ item.id +"' item='"+ item.id +"'>"
				+ item.label
				+ "<span class='edit_button'><a class='edit_button' title='<?= $this->lang->line('Edit') ?>' onclick='showEditTranscoding(\""+ item.id +"\");' onmouseover='paint_black(this)' onmouseout='unpaint_black(this)'><?= $this->lang->line('Edit') ?></a>"
				+ " <a class='edit_button' title='<?= $this->lang->line('Remove') ?>' onclick='delPlaylistItem(\""+ item.id +"\");' onmouseover='paint_black(this)' onmouseout='unpaint_black(this)'><?= $this->lang->line('Remove') ?></a></span>"
			+"</li>";
		$ul.append(li);
		
		if (play_list_items.length != 0)
			document.getElementById('playlist_label').style.color = '#838383';
		else
			document.getElementById('playlist_label').style.color = '#AFAFAF';
	}
	
	function delPlaylistItem(item_id)
	{
		var item;
		for(var i=0;i<play_list_items.length;i++)
		{
			item = play_list_items[i];
			if(item.id==item_id){
				play_list_items.splice(i, 1);
				break;
			}
		}
		$('#list_item_' + item_id).remove();
		if(play_list_items.length==0)
			document.getElementById('playlist_label').style.color = '#AFAFAF';
	}
	
	function openFilechooser()
	{
		$("#choose_fileRoot").val(goalbit.fileManager.getRootPath());
		if(OS == OS_WINDOWS){
			file_path = goalbit.fileManager.ls("");
			try{
				var arr = file_path.split("\\");
				file_name = arr[arr.length-1];
			}catch(err){
				file_name = file_path;
			}
			if(file_path!=""){
				addPlaylistItem();
			}
		}else{
			$('#choose_input').jqmHide();
			$("#choose_input_file").jqm({modal: true}).draggable();
			$("#choose_input_file").jqmShow();
			$("#choose_input_file").css('visibility', 'hidden');
			setTimeout(function() { $('#choose_input_file').css('margin-left', (($('#choose_input_file').width() + 100)*(-1)/2)); $("#choose_input_file").css('visibility', 'visible'); }, 250);
		}
	}
	
	function openAdvancedMenu()
	{
		$('#choose_input').jqmHide();
		$("#choose_advanced").jqm({modal: true}).draggable();
		$("#choose_advanced").jqmShow();
		$("#choose_advanced").css('visibility', 'hidden');
		setTimeout(function() { $('#choose_advanced').css('margin-left', (($('#choose_advanced').width() + 100)*(-1)/2)); $("#choose_advanced").css('visibility', 'visible'); }, 250);
	}
	
	function showEditTranscoding(item_id)
	{
		edit_transcode_id = item_id;
		for(var i=0 ; i < play_list_items.length ;i++)
		{
			item = play_list_items[i];
			if(item.id==item_id){
				$('#edit_transcode_value').val(item.transcode);
				document.getElementById('transcoding' + item.transcode_type).checked = true;
				if (item.transcode_type != TRANSCODE_TYPE_CUSTOM)
					document.getElementById('edit_transcode_value').disabled = true;
				break;
			}
		}
		$('#choose_input').jqmHide();
		$("#edit_transcode").jqm({modal: true}).draggable();
		$("#edit_transcode").jqmShow();
		$("#edit_transcode").css('visibility', 'hidden');
		setTimeout(function() { $('#edit_transcode').css('margin-left', (($('#edit_transcode').width() + 100)*(-1)/2)); $("#edit_transcode").css('visibility', 'visible'); }, 250);
	}
	
	function editTranscode()
	{
		for(var i=0 ; i < play_list_items.length ;i++)
		{
			item = play_list_items[i];
			if(item.id==edit_transcode_id){
				item.transcode = $('#edit_transcode_value').val();
				for(var i=0 ; i <= 3 ;i++)
					if (document.getElementById('transcoding' + i).checked)
						item.transcode_type = i;
				item.duplicate = getDuplicate(bitrate);
				break;
			}
		}
		$('#edit_transcode').jqmHide();
	}
	
	function setGoodTranscoding()
	{
		document.getElementById('edit_transcode_value').disabled = true;
		$('#edit_transcode_value').val(getGoodTranscode(transcode_ab, bitrate));
	}
	
	function setRegularTranscoding()
	{
		document.getElementById('edit_transcode_value').disabled = true;
		$('#edit_transcode_value').val(getRegularTranscode(transcode_ab, bitrate));
	}
	
	function setOriginalTranscoding()
	{
		document.getElementById('edit_transcode_value').disabled = true;
		$('#edit_transcode_value').val(getOriginalTranscode());
	}
	
	function setCustomTranscoding()
	{
		document.getElementById('edit_transcode_value').disabled = false;
	}
	
	function start_broadcasting()
	{	
		if (!broadcasting)
		{
			var cname = $("#channel_name").val();
			if (cname == "" || cname=="<?= $this->lang->line('Channel name') ?>")
				alert("<?= $this->lang->line('Channel name is not set!') ?>");
			else if(play_list_items.length == 0)
				alert("<?= $this->lang->line('Playlist is empty!') ?>");
			else
			{
				broadcasting = true;
				broadcast();
			}
		}
		else
		{
			broadcasting = false;
			stop_broadcast();
		}
	}
	
	if (window.attachEvent) window.attachEvent('onload', alCerrarBrowser);
	window.onbeforeunload = alCerrarBrowser;

	function alCerrarBrowser() {
		if (broadcasting)
		{
			broadcasting = false;
			stop_broadcast();
		}
	}
	
	function broadcast(){	
		$("#show_broadcast").jqm({modal: true}).draggable();
		$("#show_broadcast").jqmShow();
		$("#show_broadcast").css('visibility', 'hidden');
		setTimeout(function() { $('#show_broadcast').css('margin-left', (($('#show_broadcast').width() + 100)*(-1)/2)); $("#show_broadcast").css('visibility', 'visible'); }, 250);
		var cname = $("#channel_name").val();
		url = "<?= base_url() ?>start_broadcast/";
		sendAjax(
			{
				url: url,
				type: 'POST',
				data: {id:channel_id, name:cname},
				success: function(data) {
					if(data.ok){
						var item;
						del_token = data.del_token;
						for(var i=0;i<play_list_items.length;i++){
							item = play_list_items[i];
							goalbit.playlist.add(item.mrl,null,":sout="+item.transcode + item.duplicate);
						}
						showVideo();
					}
				}
			}
		);
	}
	
	function showVideo()
	{
		goalbit.playlist.playItem(0);
		goalbit.audio.volume = volValor;
		if ((MuteState == 0 && goalbit.audio.mute) || (MuteState == 1 && !goalbit.audio.mute))
			 goalbit.audio.toggleMute();
        play_list_current_timeout = -1000;
        play_list_current_index = 0;
		time_play(true);
	}
	
	function stop_broadcast(){
		url = "<?= base_url() ?>stop_broadcast/";
		sendAjax(
			{
				url: url,
				type: 'POST',
				data: {id:channel_id, del_token: del_token},
				success: function(data) {
				}
			}
		);
		stop_preview();
	}
	
	function stop_preview()
	{
		goalbit.playlist.stop();
		goalbit.playlist.items.clear();
        time_play(false);
        $("#show_broadcast").css('visibility', 'hidden');
        $("#jqmOverlay").css('visibility', 'hidden');
	}
    
	function time_play(start)
{
		if (start && (play_list_items[play_list_current_index].type == "file" || play_list_items[play_list_current_index].mrl.startsWith("file")))
        {
            $("#lbl_time").html(getMinsSegs(goalbit.input.time));
            $("#lbl_length").html(getMinsSegs(goalbit.input.length));
            if (goalbit.input.time - play_list_current_timeout < 200)
            {
                if (play_list_items.length - 1 == play_list_current_index)
                {
                    if (loop)
                        goalbit.playlist.playItem(0);
                    else
                        stop_broadcast();
                    play_list_current_index = 0;
                }
                else
                {
                    goalbit.playlist.next();
                    play_list_current_index++;
                }
                play_list_current_timeout = -1000;
            }
            else
                play_list_current_timeout = goalbit.input.time;
            timer = setTimeout("time_play(true)",1000);
		}
        else
        {
			$("#lbl_time").html(getMinsSegs(0));
			$("#lbl_length").html(getMinsSegs(0));
			clearTimeout(timer);
            play_list_current_timeout = -1000;
            play_list_current_index = -1;
		}
	}
	
	function getMinsSegs(milis){
		milis = milis/1000;
		var s = Math.round(milis%60);
		var m = Math.round((milis-s)/60);
		return (m<10 ? ("0"+m):m)+":"+(s<10 ? ("0"+s):s);
	}
	
	
	function loadFileChooser(){
		path = $("#choose_fileRoot").val();
		if(path.length > 0)
		{
			if(path[path.length - 1] != slash){
				path += slash;
				$("#choose_fileRoot").val(path);
			}
			try{
				curFolderListener(path);
				$('#files_chooser').fileTree(
					{root: path, jsonProvider: getFormatedFiles, filterProvider: getFileChooserFilter, curFolderListener:curFolderListener},
					function(fileP, fileN) {
						file_path = fileP.substr(0, fileP.length-1);//para sacar la barra
						file_name = fileN;						
						if(file_path!="")
							addPlaylistItem();
						$('#choose_input_file').jqmHide();
					}
				);
			}catch(err){
				alert(err);
			}
		}
	}
	
	var cur_folder;
	function curFolderListener(path){
		cur_folder = path;
		$("#spnCurFolder").html(cur_folder.length>40 ? ("..."+cur_folder.substr(cur_folder.length-40)):cur_folder);
	}
	
	function getFormatedFiles(path){
		return eval(goalbit.fileManager.ls(path));
	}
	
	function getFileChooserFilter(){
		return $("#choose_fileExtension").val().trim();
	}
	
	function AddFolderPlayList(){
		$('#choose_file').jqmHide();
		var arr = getFormatedFiles(cur_folder);
		var exts = $("#choose_fileExtension").val().trim();
		var regex = exts!="" ? eval("/.*\\.("+exts.replace(" ", "").replace(",","|")+")$/i") : (/.*/);
		for(var i=0;i<arr.length;i++)
			if(arr[i].is_dir == 0 && regex.test(arr[i].file_name)){
				file_name = arr[i].file_name;
				file_path = cur_folder+file_name;
				addPlaylistItem();
			}
	}
    
    function toggle_loop(a)
    {
        loop = !loop;
        if (!loop)
            $(a).css('color', '#AFAFAF');
        else
            $(a).css('color', '#838383');
    }
</script>

<table style="margin-left: auto; margin-right: auto; width: 400px" cellspacing=0 cellpadding=0 >
	<tr>
		<td colspan=2 style="text-align: center; padding-bottom: 10px">
			<input type="text" id="channel_name" class="input" value="<?= $this->lang->line('Channel name') ?>" onclick="if (this.value == '<?= $this->lang->line('Channel name') ?>') $('#channel_name').val('');" title="<?= $this->lang->line('Set the channel name') ?>" onmouseover="paint_black(this)" onmouseout="if (this.value == '<?= $this->lang->line('Channel name') ?>') unpaint_black(this);" onchange='paint_black(this)'/>
		</td>
	</tr>
	<tr >
		<td class="playlist" style="text-align: left; border-right: 0px; cursor: none" id="playlist_label">
			<?= $this->lang->line('Playlist') ?>
		</td>
		<td class="playlist" style="border-left: 0px; text-align: right; padding-right: 10px">            
			<span id="plus_button" onclick="choose_input()" title="<?= $this->lang->line('Add item') ?>" class="edit_button" onmouseover="paint_black(this)" onmouseout="unpaint_black(this)"><?= $this->lang->line('Add item') ?></span>
    		<span id="loop_button" onclick="toggle_loop(this)" title="<?= $this->lang->line('Loop') ?>" class="edit_button"  style="color: #838383; padding-right: 25px"><?= $this->lang->line('Loop') ?></span>
		</td>
	</tr>
	<tr>
		<td colspan=2 style="border: solid 1px #AFAFAF; border-top: 0px;">
			<div id="playlist" >
				<ul class="playlist_items" >
				</ul>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan=2 style="text-align: center">
			<table class="input" id="table_start_broadcasting">
				<tr>
					<td colspan=2 onclick="start_broadcasting()" id="play_btn" >
						<?= $this->lang->line('Start broadcasting!') ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>



<!-- Modals !-->

<div id="choose_input_file" class="jqmWindow">
	<table class='modal_header'>
		<tr>
			<td class='modal_title'><?= $this->lang->line('Choose file') ?></td>
			<td class='modal_close' onclick='$("#choose_input_file").jqmHide();'><a class='modal_close'><?= $this->lang->line('Close') ?></a></td>
		</tr>
		<tr>
			<td colspan=2>
				<br/>
				<div id="files_chooser" style="height: 300px;width: 400px;overflow: auto; border:1px solid #D9DFD1;margin-top:0.5em;">
				</div>
				<table style="width:100%;">
					<tr>
						<td class="label"><?= $this->lang->line('Root directory:') ?></td>
						<td style="width:16em;"><input  class="input" type="text" id="choose_fileRoot" style="width: 100%;"></td>
						<td></td>
					</tr>
					<tr>
						<td class="label"><?= $this->lang->line('Extension Filter: (mpg,mp3)') ?></td>
						<td><input type="text" class="input" id="choose_fileExtension" style="width: 100%;"></td>
						<td style="text-align: right;">
							<input class="button" type="button" value="Update" onclick="loadFileChooser();">
						</td>
					</tr>
				</table>
				<span style="float: right;margin-top:0.5em;"><?= $this->lang->line('Add folder') ?> <span id="spnCurFolder" style="font-weight: bold;"></span> <?= $this->lang->line('to the playlist') ?> <input class="button" type="button" value="Ok" onclick="AddFolderPlayList();"></span>
			</td>
		</tr>
	</table>	
</div>

<div id="choose_input" class="jqmWindow">
	<table class='modal_header'>
		<tr>
			<td class='modal_title'><?= $this->lang->line('Choose item') ?></td>
			<td class='modal_close' onclick='$("#choose_input").jqmHide();'><a class='modal_close'><?= $this->lang->line('Close') ?></a></td>
		</tr>
		<tr>
			<td colspan=2>
				<br/>
				<table style="width: 100%">
					<tr>
						<td class="input broadcast_item" id="file_btn" onmouseover="$(this).css('border-color', '#838383');" onmouseout="$(this).css('border-color', '#AFAFAF');" onclick="tmp_media_type = MEDIA_TYPE_FILE; openFilechooser();"><?= $this->lang->line('File') ?></td>
						<td>&nbsp;</td>
						<td class="input broadcast_item" id="webcam_btn" onclick="tmp_media_type = MEDIA_TYPE_WEBCAM; addPlaylistItem();" onmouseover="$(this).css('border-color', '#838383');" onmouseout="$(this).css('border-color', '#AFAFAF')"><?= $this->lang->line('Webcam') ?></td>
						<td>&nbsp;</td>
						<td class="input broadcast_item" id="advanced_btn" onclick="tmp_media_type = MEDIA_TYPE_ADVANCED; openAdvancedMenu();" onmouseover="$(this).css('border-color', '#838383');" onmouseout="$(this).css('border-color', '#AFAFAF')"><?= $this->lang->line('Advanced') ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>	
</div>


<div id="choose_advanced" class="jqmWindow">
	<table class='modal_header'>
		<tr>
			<td class='modal_title'><?= $this->lang->line('Choose advanced input') ?></td>
			<td class='modal_close' onclick='$("#choose_advanced").jqmHide();'><a class='modal_close'><?= $this->lang->line('Close') ?></a></td>
		</tr>
		<tr>
			<td colspan=2 style="width: 100%; vertical-align: top">
				<br/>
				<table>
					<tr>
						<td>
							<span class="label"><?= $this->lang->line('MRL') ?></span>
						</td>
						<td>
							<textarea id="input_advanced" class="input" name="input_advanced" rows=1 cols=300 style="width: 400px; height: 14px"></textarea>
						</td>
						<td>
							<input type="submit" name="Add" value = "<?= $this->lang->line('Add') ?>" class="button" onclick="addPlaylistItem();" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>	
</div>


<div id="edit_transcode" class="jqmWindow">
	<table class='modal_header'>
		<tr>
			<td class='modal_title'><?= $this->lang->line('Edit transcode') ?></td>
			<td class='modal_close' onclick='$("#edit_transcode").jqmHide();'><a class='modal_close'><?= $this->lang->line('Close') ?></a></td>
		</tr>
		<tr>
			<td colspan=2 style="width: 100%; vertical-align: top">
				<br/>
				<table>
					<tr>
						<td>
							<span class="label">
								<input type="radio" name="transcoding" id="transcoding0" value="0" onclick="setRegularTranscoding()"><?= $this->lang->line('Regular') ?>
								<input type="radio" name="transcoding" id="transcoding1" value="1" onclick="setGoodTranscoding()"><?= $this->lang->line('Good') ?>
								<input type="radio" name="transcoding" id="transcoding2" value="2" onclick="setOriginalTranscoding()"><?= $this->lang->line('Original') ?>
								<input type="radio" name="transcoding" id="transcoding3" value="3" onclick="setCustomTranscoding()"><?= $this->lang->line('Custom') ?>
							</span>
							<br/>
							<br/>
						</td>
					</tr>
					<tr>
						<td>
							<textarea id="edit_transcode_value" class="input" name="edit_transcode_value" rows=1 cols=300 style="width: 564px; height: 55px; "></textarea>
							<br/>
							<br/>
						</td>
					</tr>
					<tr>
						<td style="text-align: right">
							<input type="submit" name="Add" value = "<?= $this->lang->line('Edit') ?>" class="button" onclick="editTranscode();" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>	
</div>



<div id="show_broadcast" class="jqmWindow" style="display: block;">
	<table class='modal_header'>
		<tr>
			<td class='modal_title'><?= $this->lang->line('Broadcasting...') ?></td>
			<td class='modal_close' onclick='$("#choose_input").jqmHide(); start_broadcasting();'><a class='modal_close'><?= $this->lang->line('Stop and close!') ?></a></td>
		</tr>
		<tr>
			<td colspan=2>
				<br/>
				<div class="player">
					<OBJECT classid="clsid:A34B8124-596C-4dc8-9580-785A7BD5A3DE"
							width="400"
							height="300"
							id="goalbit"
							events="True"
							autoplay="no">
							<param name="MRL" value="" />
							<param name="ShowDisplay" value="True" />
							<param name="AutoPlay" value="False" />
							<param name="Volume" value="50" />
							<param name="toolbar" value="true" />
							<param name="StartTime" value="0" />
                            <param name="b_yourself" value="true" />
							<EMBED type="application/x-goalbit-plugin"
								   width="400"
								   height="300"
								   id="goalbit"
                                   b_yourself="true"
								   name="goalbit">
							</EMBED>
					</OBJECT>
				</div>
				<div id="broadcaster_buttons" style="border: solid 1px #E0E0E0; ">
						&nbsp;
                        
                        <img class="playing" src="<?= base_url() ?>images/mute1.png" id="ButtonMute" style="border: 1px solid #CCCCCC; float: left; margin-top: 4px; margin-left: 4px;" onclick='muteClick(event)' onmouseover="mutemouseover(event)" onmouseout="mutemouseout(event)"></img>
                        
                        <img src="<?= base_url() ?>images/volumebar.png" id="VolumeBar" style="float: left; width: 0px; height: 15px; z-index: 0; margin-top: 4px; margin-left: -4px" onmouseout="volumebarout(event)" onmouseover="volumebarover(event)" onmousemove="volumebarover(event)" onmousedown="volumebardown(event)" onmouseup="volumebarup()"></img>

                        <img src="<?= base_url() ?>images/volumepipe.png" id="VolumePipe" style="float: left; width: 0px;  z-index: 1; margin-top: 8px; margin-left: -14px" onmouseout="volumebarout(event)" onmouseover="volumebarover(event)" onmousedown="volumebardown(event)" onmouseup="volumebarup()"></img>
						
						<span style="float: right; color: #898989; margin: 6px 4px 4px 4px; text-shadow: #E3E3E3 0px 0px 1px;"><label id="lbl_time" style="">00:00</label>/<label id="lbl_length">00:00</label></span>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan=2 style="text-align: center; padding-top: 10px">
				<a class="operations" style="cursor: pointer" href="<?= base_url().'channel_list/get_html/'.$channel_id ?>" onclick="window.open('<?= base_url().'channel_list/get_html/'.$channel_id ?>'); return false;"><?= $this->lang->line('Share it!') ?></a>
			</td>
		</tr>
	</table>	
</div>

<script>
	//Vueltita para IE
	$(document).ready(function() 
	{
		$("#show_broadcast").css('visibility', 'hidden');
	});
	$('#content').css('min-width', $('#playlist').width());
	$('#tabsycontent').css('min-width', $('#playlist').width());
</script>

<?php $this->load->view('footer'); ?>
