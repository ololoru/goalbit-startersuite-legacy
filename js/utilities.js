function set_modal(url, reload, draggable)
{
	var cerrar=function(hash) 
	{ 
		hash.w.fadeOut('2000',function(){ hash.o.remove();}); 
		if (reload)
			window.location = '<?= base_url() ?>channel_list';
	}; 
	
	var centrar = function()
	{
		var a = ($('#div_modal').width() + 100)*(-1)/2;
		$('#div_modal').css('margin-left', a);
	};
	
	$('#div_modal').jqm({ajax: url, modal: true, onHide:cerrar, onLoad: centrar});
	if (draggable)
		$('#div_modal').draggable();
	$('#div_modal').jqmShow();
}

function sendAjax(opts){
    if($.browser.msie){
        //Se hace esto por un bug que hay con IE8
        opts.xhr = (window.ActiveXObject) ?
                function() {
                    try {
                        return new window.ActiveXObject("Microsoft.XMLHTTP");
                    } catch(e) {}
                } :
                function() {
                    return new window.XMLHttpRequest();
                };
    }
    $.ajax(opts);
}

String.prototype.startsWith = function(str) 
{return (this.match("^"+str)==str)}