// jQuery File Tree Plugin
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// Visit http://abeautifulsite.net/notebook.php?article=58 for more information
//
// Usage: $('.fileTreeDemo').fileTree( options, callback )
//
// Options:  root           - root folder to display; default = /
//           jsonProvider
//           folderEvent    - event to trigger expand/collapse; default = click
//           expandSpeed    - default = 500 (ms); use -1 for no animation
//           collapseSpeed  - default = 500 (ms); use -1 for no animation
//           expandEasing   - easing function to use on expand (optional)
//           collapseEasing - easing function to use on collapse (optional)
//           multiFolder    - whether or not to limit the browser to one subfolder at a time
//           loadMessage    - Message to display while initial tree loads (can be HTML)
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// TERMS OF USE
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC. 
//

if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			// Defaults
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '/';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();

                    var data = formatHTML(t);
                    $(c).find('.start').html('');
                    $(c).removeClass('wait').append(data);
                    if( o.root == t ) $(c).find('UL:hidden').show(); else $(c).find('UL:hidden').slideDown({duration: o.expandSpeed, easing: o.expandEasing});
                    bindTree(c);

				}

                function formatHTML(path){
                    path=decodeURIComponent(path);

                    var slash;
                    slash = OS == OS_WINDOWS ? "\\" : "/";
                    if(path[path.length - 1] != slash) path += slash;                    

                    var arr = o.jsonProvider(path);//eval(goalbit.fileManager.ls(path));
                    
                    var ret = "<ul class='jqueryFileTree' style='display: none;'>";
                    var clas;
                    var exts = o.filterProvider ? o.filterProvider():"";
                    var regex = exts!="" ? eval("/.*\\.("+exts.replace(" ", "").replace(",","|")+")$/i") : (/.*\.(\w*)$/i);
                    try{
                        for(var i=arr.length-1;i>0;i--){//Es mayor que cero porque la primera pos es dummy
                            var item = arr[i];
                            if(item.is_hidden!=1){
                                if(item.is_dir == 1){
                                    clas = "directory collapsed";
                                }else{
                                    clas = "file ";
                                    if(regex.test(item.file_name)){
                                        clas += "ext_"+regex.exec(item.file_name)[1];
                                    }else if(exts!=""){
                                        continue;
                                    }
                                }


                                ret += ("<li class='"+ clas +"'><a href='#' rel='"+path+item.file_name+ slash +"'>"+item.file_name+"</a></li>");
                            }
                        }
                    }catch(err){}
                    return ret + "</ul>";
                }
				
                function bindTree(t) {
                        $(t).find('LI A').bind(o.folderEvent, function() {
                                if( $(this).parent().hasClass('directory') ) {
                                        if( $(this).parent().hasClass('collapsed') ) {
                                                // Expand
                                                if( !o.multiFolder ) {
                                                        $(this).parent().parent().find('UL').slideUp({duration: o.collapseSpeed, easing: o.collapseEasing});
                                                        $(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
                                                }
                                                $(this).parent().find('UL').remove(); // cleanup
                                                showTree( $(this).parent(), $(this).attr('rel'));//escape($(this).attr('rel').match( /.*\// )) );
                                                $(this).parent().removeClass('collapsed').addClass('expanded');
                                        } else {
                                                // Collapse
                                                $(this).parent().find('UL').slideUp({duration: o.collapseSpeed, easing: o.collapseEasing});
                                                $(this).parent().removeClass('expanded').addClass('collapsed');
                                        }
                                        if(o.curFolderListener){
                                            o.curFolderListener($(this).attr('rel'));
                                        }
                                } else {
                                        h($(this).attr('rel'),this.innerHTML);
                                }
                                return false;
                        });
                        // Prevent A from triggering the # on non-click events
                        if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() {return false;});
                }
                // Loading message
                $(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
                // Get the initial file list
                showTree( $(this), escape(o.root) );
        });
        }
    });
	
})(jQuery);