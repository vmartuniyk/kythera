var SEARCH_MODE_MYSQL = 1;
var SEARCH_MODE_ELASTIC = 2;
var SEARCH_MODE = SEARCH_MODE_ELASTIC;

//http://jqueryvalidation.org/validate
var ruleSetDefault = {
	debug: false,
	ignore: [],
	errorElement: "span",
	errorClass: "form-error",
	errorPlacement: function(error, element)
	{
		if (element.is('textarea')) {
			(typeof CKEDITOR != 'undefined') ? error.insertAfter(element.next()) : error.insertAfter(element);
		} else
		if (element.is('input:checkbox')) {
			error.insertAfter(element.parent());
		} else
			error.insertAfter(element);
	}
}
//console.log(ruleSetDefault);

//http://docs.ckeditor.com/#!/api/CKEDITOR.config
var ckeditorDefault = {
    toolbarCanCollapse: true,
    xtoolbar: [
              [ 'Source', '-', 'Bold', 'Italic' ]
              ],
    toolbarGroups: [
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                    { name: 'links' },
                    { name: 'insert' },
                    { name: 'styles' }
                ],
    contentsCss: '/assets/css/ckeditor.css'
}

//https://api.jqueryui.com/datepicker/#utility-formatDate
var	datepickerDefault = {dateFormat: "dd/mm/yy"};

var pluploadDefault = {
	//kfn: Enable captioning the image
	caption: false,
	//kfn
	single: true, //multi/single mode
	//kfn: Rename files by clicking on their titles
	rename: false,
	// General settings
	runtimes : 'html5,flash,silverlight,html4',
	//url : '/xhtml/plupload/examples/upload.php',
	url : '/en/entry/upload',
	// User can upload no more then 20 files in one go (sets multiple_queues to false)
	max_file_count: 10,

	chunk_size: '1mb',
	// Resize images on clientside if we can
	resize : {
		width : 200,
		height : 200,
		quality : 90,
		crop: true // crop to exact dimensions
	},
    thumb_width: 200,
    thumb_height: 100,
	filters : {
		// Maximum file size
		max_file_size : '20mb',
		// Specify what files to browse for
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Audio files", extensions : "wav,mp3,wma"},
			{title : "Video files", extensions : "flv,mp4"}
		]//,
		//kfn: Max queue count
		//max_queue_count: 4
	},
	max_queue_count: 4,
	//filters : {},
	// Sort files
	sortable: false,
	// Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
	dragdrop: true,
	// Views to activate
	views: {
		list: false,
		thumbs: true, // Show thumbs
		active: 'thumbs'
	},
	// Flash settings
	flash_swf_url : '../../js/Moxie.swf',
	flash_swf_url : '/xhtml/plupload/js/Moxie.swf',
	flash_swf_url : '/assets/vendors/plupload/js/Moxie.swf',
	// Silverlight settings
	silverlight_xap_url : '/xhtml/plupload/js/Moxie.xap',
	silverlight_xap_url : '/assets/vendors/plupload/js/Moxie.xap',
	//Auto start uploading new added files
	autostart: true,
	multiple_queues: false
}

jQuery(function() {
	//back-top-top
    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.back-to-top').fadeIn(duration);
        } else {
            jQuery('.back-to-top').fadeOut(duration);
        }
    });

    jQuery('.back-to-top').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })

    //txtdoc-filter
	jQuery('.auto-submit').bind('change', function() {
		$(this).parent().submit()
	} );

	//Confirm modal box
    jQuery('#confirm-delete').on('show.bs.modal', function (e) {
		$message = $(e.relatedTarget).data('message');
		$(this).find('.modal-body p').text($message);
		$title = $(e.relatedTarget).data('title');
		$(this).find('.modal-title').text($title);

		$action = $(e.relatedTarget).data('action');
		$(this).find('.modal-content form').attr('action', $action);
	});

	jQuery.validator.addMethod("dateITA", function(value, element) {
		var check = false,
			re = /^\d{1,2}\/\d{1,2}\/\d{4}$/,
			adata, gg, mm, aaaa, xdata;
		if ( re.test(value)) {
			adata = value.split("/");
			gg = parseInt(adata[0], 10);
			mm = parseInt(adata[1], 10);
			aaaa = parseInt(adata[2], 10);
			xdata = new Date(aaaa, mm - 1, gg, 12, 0, 0, 0);
			if ( ( xdata.getUTCFullYear() === aaaa ) && ( xdata.getUTCMonth () === mm - 1 ) && ( xdata.getUTCDate() === gg ) ) {
				check = true;
			} else {
				check = false;
			}
		} else {
			check = false;
		}
		return this.optional(element) || check;
	}, "Please enter a correct date dd/mm/yyyy.");

	jQuery.validator.addMethod("ckeditor", function(value, element) {
		var element, editor;//console.log("ckeditor: " + value);
		var value = '';
		element = $(element);//console.log("ckeditor: " + element.attr('id'));
		if (typeof CKEDITOR != 'undefined') {
			editor  = CKEDITOR.instances[element.attr('id')];//console.log(editor);
			editor.updateElement();
			value = editor.getData().replace(/<[^>]*>/gi, '');//console.log("ckeditor: " + value);
			if (value.length == 0) editor.focus();
		}
		return value.length != 0;
	}, "This field is required." );
});

function search_mysql()
{
	//https://github.com/twitter/typeahead.js
	//http://mycodde.blogspot.com.es/2014/12/typeaheadjs-autocomplete-suggestion.html

	// remote
	// ------
	if ('undefined' == typeof Bloodhound) return;

	var search = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		prefetch: '/en/suggest',
		remote: {
			url: '/en/suggest?q=%QUERY',
			wildcard: '%QUERY'
		}
	});

	$('#remote .typeahead').typeahead({
		highlight: true,
		hint: true,
		minLength: 3
	}, {
		name: 'search',
		source: search.ttAdapter(),
		display: 'value',
		limit: 5,
		async: true,
		templates: {
			 empty: [
				'<div class="tt-suggestion">',
				'Nothing found on your search query...',
				'</div>'
			].join('\n'),
		    suggestion: function(data)
		    {
		        return '<div class="tt-suggestion"><h2>' + data.crumbs + '</h2><span class="d">' + data.year + '</span> ' + data.value + ' ' + data.label + '</div>';
		    },
		    pending: function(data)
		    {
		    	$('#bloodhound i.fa').css('color', 'red');
		    },
		    footer: function(data)
		    {
		    	var c = data.suggestions.length;
		    	if (c)
		    	return '<div class="tt-more"><div class="line"></div><a href="'+endpoint+'?q='+data.query+'">More results...</a></div>';
		    	//return '<div class="tt-more"><div class="line"></div><a href="/en/search?q='+data.query+'">More results...</a></div>';
		    }
		}

	}).on('typeahead:selected', function($e, data)
	{
		document.location.href = data.url;
	});
}


function showSpinner() {jQuery('.header-menu li.search .spinner').show()}
function hideSpinner() {jQuery('.header-menu li.search .spinner').hide()}
function search_elastic()
{
	//https://github.com/twitter/typeahead.js
	//http://mycodde.blogspot.com.es/2014/12/typeaheadjs-autocomplete-suggestion.html

	var MIN_LENGTH = 1;

	// remote
	// ------
	if ('undefined' == typeof Bloodhound) return;

	var search = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		xprefetch: '/en/suggest',
		remote: {
			url: '/en/suggest/'+MIN_LENGTH+'?q=%QUERY',
			wildcard: '%QUERY',
			replace: function(url, query){
				showSpinner();
	            return url+encodeURIComponent(query);
            },
            filter: function(parsedResponse){
            	hideSpinner();
            	return parsedResponse;
            }
		}
	});

	$('#remote .typeahead').typeahead({
		highlight: true,
		hint: false,
		minLength: MIN_LENGTH
	}, {
		name: 'search',
		source: search.ttAdapter(),
		display: 'value',
		limit: 7,
		async: true,
		templates: {
			 empty: [
				'<div class="tt-suggestion">',
				'Nothing found on your search query...',
				'</div>'
			].join('\n'),
		    suggestion: function(data)
		    {
		    	var h = '';
		    	h += '<div class="tt-suggestion">';
		    	h += '<h2>' + data.crumbs + '</h2>';
		    	h += '<span class="d">' + data.year + '</span> ';
		    	h += data.title + '<br>';
		    	h += '<span class="v">' + data.value + '</span> ';
		    	//h += data.label;
		    	h += '</div>';
		        return h;
		    },
		    pending: function(data)
		    {
		    	$('#bloodhound i.fa').css('color', 'red');
		    },
		    footer: function(data)
		    {
		    	var c = data.suggestions.length;
		    	if (c)
	    		return '<div class="tt-more"><div class="line"></div><a href="'+endpoint+'?q='+data.query+'">More results...</a></div>';
		    	//return '<div class="tt-more"><div class="line"></div><a href="/en/search?q='+data.query+'">More results...</a></div>';
		    }
		}

	}).on('typeahead:selected', function($e, data)
	{
		document.location.href = data.url;
	});
}

jQuery(function(){
	if (SEARCH_MODE == SEARCH_MODE_MYSQL) search_mysql();
	else if (SEARCH_MODE == SEARCH_MODE_ELASTIC) search_elastic();
	else alert('Search disabled');
});


function audio(control, i) {
	//http://jplayer.org/latest/demo-03-video/
	var a = $( '<div id="audio'+i+'"></div' );
    $(a).jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", {mp3: control.attr('href')});
            control.click(function(){a.jPlayer('play');return false});
        },
        play: function(event) {
        	$(this).jPlayer("pauseOthers");
            control.css('background-image', 'url(/xhtml/img/pause-button.png)');
            control.click(function(){a.jPlayer('pause');return false});
        },
        pause: function(event) {
            control.css('background-image', 'url(/xhtml/img/play-button.png)');
            control.click(function(){a.jPlayer('play');return false});
        }

    });
    control.append(a);
}

function audioView(control, i) {
	//http://jplayer.org/latest/demo-03-video/
	var a = $( '<div id="audio'+i+'"></div' );
	$(a).jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {mp3: control.attr('href')});
			control.click(function(){a.jPlayer('play');return false});
		},
		play: function(event) {
			$(this).jPlayer("pauseOthers");
			//control.css('background-image', 'url(/xhtml/img/pause-button.png)');
			control.click(function(){a.jPlayer('pause');return false});
		},
		pause: function(event) {
			//control.css('background-image', 'url(/xhtml/img/play-button.png)');
			control.click(function(){a.jPlayer('play');return false});
		}

	});
	control.append(a);
}

function videoView(control, i) {
	var options = $(control).data();
	//console.log(options);

	//handle serverside video convertion delay
	if ("" == options.supplied) {
		var a = $('<div style="width:510px;height:120px"><div style="height:100px;width:100px;" class="thumb media video">&nbsp;</div><p>Please wait a few minutes while were optimizing the video format...</p></div>');
		control.append(a);
		return;

		var a = $('<div style="width:510px;height:120px"><div style="height:100px;width:100px;" class="thumb media video">&nbsp;</div><p>Please wait a few seconds while were optimizing the video format...<span id="w">10</span></p></div>');
		control.append(a);
		var r = 10,
			t;
		setTimeout(function() {
			t = setInterval(timer, 1000);
		}, 1000);
		function timer() {
			r--;
			$('#w').html(r);
			if (r<=0) {
				clearInterval(t);
				location.reload(true);
			}
		}
		return;
	}

	var d = [
         '<div id="jp_container_'+i+'" class="jp-video jp-video-270p" role="application" aria-label="media player">',
         '<div class="jp-type-single">',
         '<div class="overlay"><h2>'+options.title+'</h2><div class="play"></div></div>',
         '<div id="jquery_jplayer_'+i+'" class="jp-jplayer"></div>',
 		'<div class="jp-gui">																																																																															',
 		'    <div class="jp-interface">                                                                                                                                                   ',
 		'        <div class="jp-progress">                                                                                                                                                ',
 		'            <div class="jp-seek-bar">                                                                                                                                            ',
 		'                <div class="jp-play-bar"></div>                                                                                                                                  ',
 		'            </div>                                                                                                                                                               ',
 		'        </div>                                                                                                                                                                   ',
 		'        <div class="jp-controls-holder">                                                                                                                                         ',
 			'        <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div><span class="jp-time-seperator">/</span>                                                                                                 ',
 			'        <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>                                                                                                 ',
 			'            <div class="jp-volume-controls">                                                                                                                                     ',
 			'                <button class="jp-mute" role="button" tabindex="0">mute</button>                                                                                                 ',
 			'                <button class="jp-volume-max" role="button" tabindex="0">max volume</button>                                                                                     ',
 			'                <div class="jp-volume-bar">                                                                                                                                      ',
 			'                    <div class="jp-volume-bar-value"></div>                                                                                                                      ',
 			'                </div>                                                                                                                                                           ',
 			'            </div>                                                                                                                                                               ',
 			'            <div class="jp-controls">                                                                                                                                            ',
 			'                <button class="jp-play" role="button" tabindex="0">play</button>                                                                                                 ',
 			'                <button class="jp-stop" role="button" tabindex="0">stop</button>                                                                                                 ',
 			'            </div>                                                                                                                                                               ',
 			'            <div class="jp-toggles">                                                                                                                                             ',
 			'                <button class="jp-full-screen" role="button" tabindex="0">full screen</button>                                                                                   ',
 			'            </div>                                                                                                                                                               ',
 		'        </div>                                                                                                                                                                   ',
 		'    </div>                                                                                                                                                                       ',
 		'</div>                                                                                                                                                                           ',
 		'<div class="jp-no-solution">                                                                                                                                                     ',
 		'    <span>Update Required</span>                                                                                                                                                 ',
 		'    To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>',
 		'</div>',
 		'</div>',
 		'</div>'
 		].join('\n');
 	d = $(d);
 	control.append(d);

	var o = $("#jp_container_"+i+" .overlay");
	var f = $("#jquery_jplayer_"+i);
	var p = $(f).jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", options);
            if (options.autoplay) $(this).jPlayer("play");
        },
        ended: function (event) {
        	//show poster
        	f.find('img').css('display', 'inline');
        	f.find('video').css({'width':'0','height':'0'});
        	f.find('object').css({'width':'0','height':'0'});

        	//show overlay
        	o.fadeIn(1000);
        },
        play: function(event) {
        	$(this).jPlayer("pauseOthers");

        	//re-enable click canvas to pause
        	f.unbind("click");
        	f.click(function(){p.jPlayer('pause');return false});

        	//hide poster
        	f.find('img').css('display', 'none');
        	f.find('video').css({'width':'480px','height':'270px'});
        	f.find('object').css({'width':'480px','height':'270px'});

        	//hide overlay
        	o.fadeOut(1000);
        },
        pause: function(event) {
        	//re-enable click canvas to play
        	f.unbind("click");
        	f.click(function(){p.jPlayer('play');return false});
        },
		solution: "html,flash",
        swfPath: "/assets/vendors/jplayer/dist/jplayer",
        xsupplied: "m4v, ogv",
        supplied: options.supplied,
        cssSelectorAncestor: "#jp_container_"+i,
        globalVolume: true,
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true
	});
	o.click(function(){p.jPlayer('play');return false});
}

jQuery(function(){
	/*
	 * Create audio elements
	 */
	$('.play.audio').each(function(i){
		audio($(this), ++i)
	});

	$('.view.audio').each(function(i){
		audioView($(this), ++i)
	});

	/*
	 * Create video elements
	 */
	/*
	$('.play.video').each(function(i){
		audio($(this), ++i)
	});
	*/

	$('.view.video').each(function(i){
		videoView($(this), ++i)
	});

});

/*
jQuery(function(){
	$body = jQuery("#info");

	jQuery(document).on({
		ajaxStart: function() { $body.addClass("loading"); },
		ajaxStop: function() { $body.removeClass("loading"); }
	});
});
*/


window.laravelCookieConsent = (function () {

	var COOKIE_VALUE = 1;

	function consentWithCookies() {
		setCookie('kfn_cookie_consent', COOKIE_VALUE, 365 * 20);
		hideCookieDialog();
	}

	function cookieExists(name) {
		return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
	}

	function hideCookieDialog() {
		var dialogs = document.getElementsByClassName('js-cookie-consent');

		for (var i = 0; i < dialogs.length; ++i) {
			dialogs[i].style.display = 'none';
		}
	}

	function setCookie(name, value, expirationInDays) {
		var date = new Date();
		date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
		// document.cookie = name + '=' + value + '; ' + 'expires=' + date.toUTCString() +';path=/';
		document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/' + ';secure';
	}

	if(cookieExists('kfn_cookie_consent')) {
		hideCookieDialog();
	}

	var buttons = document.getElementsByClassName('js-cookie-consent-agree');

	for (var i = 0; i < buttons.length; ++i) {
		buttons[i].addEventListener('click', consentWithCookies);
	}

	return {
		consentWithCookies: consentWithCookies,
		hideCookieDialog: hideCookieDialog
	};
})();