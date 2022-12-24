/*
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});
*/

var ckeditorDefault = {
    toolbarCanCollapse: true,
    xtoolbar: [
              [ 'Source', '-', 'Bold', 'Italic' ]
              ],
    xtoolbarGroups: [
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                    { name: 'links' },
                    { name: 'insert' },
                    { name: 'styles' }
                ],
    contentsCss: '/assets/css/ckeditor.css',
    allowedContent: 'br(clear); hr(clear); h1 h2 i b; a[href,title,name]; img[!src,alt]; div(col-md-*,team,teams)'
}

jQuery(document).ready(function() {
	
	//Confirm modal box
	$('#confirm-delete').on('show.bs.modal', function (e) {
		$message = $(e.relatedTarget).data('message');
		$(this).find('.modal-body p').text($message);
		$title = $(e.relatedTarget).data('title');
		$(this).find('.modal-title').text($title);
		
		$action = $(e.relatedTarget).data('action');
		$(this).find('.modal-content form').attr('action', $action);
	});

	
	//Update folder/page order
	$( ".sortable" ).sortable({
		placeholder: "ui-state-placeholder",
		connectWith: ".connectedSortable",
		update: function( event, ui ) {
			var ul = jQuery(ui.item).closest('ul.sortable');
			var f  = jQuery(ul).attr('id');
			var sortedIDs = jQuery(ul).sortable("toArray");
			var token = $('meta[name="csrf-token"]').attr('content');
			cms.command(null, {"c":"OrderPages", "p": {"f":f, "p":sortedIDs} });
		},
		change: function( event, ui ) {
		}
	
	}).disableSelection();


    /*
    //Toggle navigation tree
    $(".folders h2 i.tree").each(function(i) {
        $(this).click(function(){
            var control = $(this);
            var folder  = '#'+$(this).data('folder');
            !$(folder).is(':visible') ? $(control).removeClass( "glyphicon-collapse-down").addClass( "glyphicon-expand" )
                                      : $(control).removeClass( "glyphicon-expand").addClass( "glyphicon-collapse-down" );
            $(folder).fadeToggle('slow','linear');
        });
        if (i==0) $(this).click();
    })
    */

});


/*
 * Javascript admin framework
 * 
 */
var cms = {
    version: 0.1,
    locale: 'en',
    debug: false,
    boot: function(params) {
    	cms.version = params.version;
    	cms.debug   = params.debug;
    	cms.locale  = params.locale;
    	if (cms.debug)
    	console.log(cms);
    },
    command: function(sender, request) {
    	$.post('/en/admin/command', {
    		'_token': $('meta[name="csrf-token"]').attr('content'),
    		'request':request 
    		}, function(response) {
    			if (cms.debug) {
    				console.log(response);
    			}
    			if (request.c == 'ToggleActive') {
	    			var icon   = sender.find("i.glyphicon");
	    			var active = icon.hasClass("glyphicon-eye-open");
	    			active ? icon.removeClass("glyphicon-eye-open glyphicon-eye-close").addClass("glyphicon-eye-close")
	    				   : icon.removeClass("glyphicon-eye-open glyphicon-eye-close").addClass("glyphicon-eye-open");
	    			var href = sender.parent().find("a.active, a.inactive");
	    			active ? href.removeClass("active inactive").addClass("inactive")
	    			       : href.removeClass("active inactive").addClass("active");
    			}
    			if (!response.result) {
    				alert(response.message);
	    			if (response.reload) {
	    				window.location.reload(true); 	
	    			}
    				return;
    			}
    			if (response.message) {
    				alert(response.message);
    			}
    			if (response.reload) {
    				window.location.reload(true); 	
    			}
    		}, 'json');
    }
}



/*	user search input
	@francesdath 2017-06-06
--------------------------------------------------------------- */

jQuery(document).ready(function() {

	$( '.form-group.admin-search' ).submit( function() {

		//	admin users page

		location.href = '?s=' + $( this ).serialize();

		//	admin user edit page (have to redirect back to users)

		if ( window.location.href.indexOf( 'users' ) > -1 && window.location.href.indexOf( 'edit' ) > -1 ) {

			var pathname = window.location.pathname,
				n = pathname.indexOf( 'users' ),
				pathname = pathname.substring( 0, n != -1 ? n : pathname.length ),
				pathname = pathname + 'users?' + $( this ).serialize();
			
			window.location.href = pathname;

			return false;

		}

	});
	



});





