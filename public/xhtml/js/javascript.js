/*
jQuery(document).ready(function() {
	jQuery('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
		// Avoid following the href location when clicking
		event.preventDefault(); 
		// Avoid having the menu to close when clicking
		event.stopPropagation(); 
		// If a menu is already open we close it
		//$('ul.dropdown-menu [data-toggle=dropdown]').parent().removeClass('open');
		// opening the one you clicked on
		jQuery(this).parent().addClass('open');
		
		var menu = $(this).parent().find("ul");
		var menupos = menu.offset();
		
		if ((menupos.left + menu.width()) + 30 > $(window).width()) {
		    var newpos = - menu.width();      
		} else {
		    var newpos = $(this).parent().width();
		}
		menu.css({ left:newpos });
	});
});
*/


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

//var v;
function videoView(control, i) 
{
	var d = [
        '<div id="jp_container_'+i+'" class="jp-video jp-video-270p" role="application" aria-label="media player">',
        '<div class="jp-type-single">',
        '<div class="overlay"><h2>Title</h2><div class="play"></div></div>',
        '<div id="jquery_jplayer_'+i+'" class="jp-jplayer" style="outline:1px solid red"></div>',
		'<div class="jp-gui">																																																																															',
		//'    <div class="jp-video-play">                                                                                                                                                  ',
		//'        <button class="jp-video-play-icon" role="button" tabindex="0">play</button>                                                                                              ',
		//'    </div>                                                                                                                                                                       ',
		'    <div class="jp-interface">                                                                                                                                                   ',
		'        <div class="jp-progress">                                                                                                                                                ',
		'            <div class="jp-seek-bar">                                                                                                                                            ',
		'                <div class="jp-play-bar"></div>                                                                                                                                  ',
		'            </div>                                                                                                                                                               ',
		'        </div>                                                                                                                                                                   ',
		//'        <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>                                                                                                 ',
		//'        <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>                                                                                                 ',
		//'        <div class="jp-details">                                                                                                                                                 ',
		//'            <div class="jp-title" aria-label="title">&nbsp;</div>                                                                                                                ',
		//'        </div>                                                                                                                                                                   ',
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
			//'                <button class="jp-repeat" role="button" tabindex="0">repeat</button>                                                                                             ',
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
            $(this).jPlayer("setMedia", {
				xtitle: "Big Buck Bunny Trailer",
				flv: "http://kfn.laravel.debian.mirror.virtec.org/video/small.flv",
				m4v: "http://kfn.laravel.debian.mirror.virtec.org/video/small.m4v",
				ogv: "http://kfn.laravel.debian.mirror.virtec.org/video/small.ogv",
				mp4: "http://kfn.laravel.debian.mirror.virtec.org/video/small.mp4",
				xm4v: "http://kfn.laravel.debian.mirror.virtec.org/video/x.m4v",
				xposter: "http://kfn.laravel.debian.mirror.virtec.org/video/posters/poster.jpg",
				poster: "http://kfn.laravel.debian.mirror.virtec.org/video/posters/6.jpg",
				xposter: "http://kfn.laravel.debian.mirror.virtec.org/video/posters/small.jpg",
				xsize: {
					width: "640px",
					height: "360px",
					cssClass: "jp-video.360p"
				}
            });
            //$(this).jPlayer("play", 19); //$(this).jPlayer("pause");
            console.log('ready');
        },
        ended: function (event) {
        	console.log('ended');
        	
        	//show poster
        	f.find('img').css('display', 'inline');
        	//f.find('img').fadeIn(1000);
        	f.find('video').css({'width':'0','height':'0'});
        	f.find('object').css({'width':'0','height':'0'});
        	//f.find('video').hide();

        	//show overlay        	
        	o.show();
        	
        	//$("#jp_container_"+i+" .overlay").show();
        	//show poster
        	//f.find('img').show();
        	//f.find('video').hide();
        },
        play: function(event) {
        	console.log('play');
        	
        	$(this).jPlayer("pauseOthers");
        	
        	//re-enable click canvas to pause
        	f.unbind("click");
        	f.click(function(){p.jPlayer('pause');return false});
        	
        	//hide poster
        	f.find('img').css('display', 'none');
        	f.find('video').css({'width':'480px','height':'270px'});
        	f.find('object').css({'width':'480px','height':'270px'});
        	
        	//hide overlay        	
        	o.hide();
        	
        	//show video
        	//f.find('img').hide();
        	//f.find('video').show();
        	
        	//control.click(function(){p.jPlayer('pause');return false});
        	//$("#jquery_jplayer_"+i+" .jp-interface").slideDown();
        	//
        	
        	/*
        	$("#jquery_jplayer_"+i).click(function() {
        		$(p).jPlayer("pause");
        	});
        	*/
        },
        pause: function(event) {
        	console.log('pause');
        	
        	//re-enable click canvas to play
        	f.unbind("click");
        	f.click(function(){p.jPlayer('play');return false});
        	//$("#jp_container_"+i+" .overlay").toggle();
        	/*
        	$("#jp_container_"+i).click(function() {
        		$(p).jPlayer("play");
          	});
        	 */
        	//control.click(function(){p.jPlayer('play');return false});
        },
		solution: "xhtml,xflash",
		solution: "html,flash",
        xsolution: "flash,html",
        swfPath: "/assets/vendors/jplayer/dist/jplayer",
        xsupplied: "m4v, ogv",
        xsupplied: "flv",
        supplied: "m4v",
        xsupplied: "ogv",
        cssSelectorAncestor: "#jp_container_"+i,
        globalVolume: true,
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
	});
	o.click(function(){p.jPlayer('play');return false});
}

//var p = null;

/*
function play(sender) {
	$(p).jPlayer("play");
}
*/


jQuery(function() {
	$('.view.video').each(function(i){
		videoView($(this), ++i)
		//videoView($(this), i+3)
	});
	return;
	
//http://kfn.laravel.debian.mirror.virtec.org/en/video/7/1460844415.flv
//http://kfn.laravel.debian.mirror.virtec.org/video/sample.m4v
		
	p = $("#jquery_jplayer_1").jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", {
				flv: "http://kfn.laravel.debian.mirror.virtec.org/video/small.flv",
				xm4v: "http://kfn.laravel.debian.mirror.virtec.org/video/small.m4v",
				ogv: "http://kfn.laravel.debian.mirror.virtec.org/video/small.ogv",
				mp4: "http://kfn.laravel.debian.mirror.virtec.org/video/small.mp4",
				m4v: "http://kfn.laravel.debian.mirror.virtec.org/video/x.m4v",
				xposter: "http://kfn.laravel.debian.mirror.virtec.org/video/posters/poster.jpg",
				xposter: "http://kfn.laravel.debian.mirror.virtec.org/video/posters/6.jpg",
				poster: "http://kfn.laravel.debian.mirror.virtec.org/video/posters/small.jpg",
            });
            //$(this).jPlayer("play", 10); $(this).jPlayer("pause");
        },
        solution: "html,flash",
        ended: function (event) {
        	//rewind and play
            $(this).jPlayer("play", 0);
        },
        swfPath: "/assets/vendors/jplayer/dist/jplayer",
        supplied: "m4v, ogv",
        supplied: "flv",
        supplied: "m4v",
        supplied: "m4v, ogv",
        cssSelectorAncestor: "#jp_container_1",
        globalVolume: true,
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
		
        play: function(event) {
        	console.log("pause1");
        	$(this).jPlayer("pauseOthers");
        	$("#jp_container_1 .overlay").toggle();
        	
        	$("#jquery_jplayer_1").click(function() {
        		console.log("pause");
        		$(this).jPlayer("pause");
        	});
        },
        pause: function(event) {
        	console.log("play1");
        	$("#jp_container_1 .overlay").toggle();
        },
        stop: function(event) {
        	console.log("stop");
        }
    });
	/*
	$("#jp_container_1 .overlay").click(function(e) {
		$(p).jPlayer("play");
		e.preventDefault();
	});
	*/
	
	

	$("#jquery_jplayer_2").jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", {
				title: "Big Buck Bunny Trailer",
				flv: "http://kfn.laravel.debian.mirror.virtec.org/video/small.flv",
				m4v: "http://kfn.laravel.debian.mirror.virtec.org/video/small.m4v",
				ogv: "http://kfn.laravel.debian.mirror.virtec.org/video/small.ogv",
				poster: "http://kfn.laravel.debian.mirror.virtec.org/video/poster.jpg",
				xsize: {
					width: "640px",
					height: "360px",
					cssClass: "jp-video.360p"
				}
            });
            //$(this).jPlayer("play", 0); 
        },
        ended: function (event) {
            $(this).jPlayer("play", 0);
        },
		play: function(event) {
			$(this).jPlayer("pauseOthers");
			control.click(function(){a.jPlayer('pause');return false});
		},
		pause: function(event) {
			control.click(function(){a.jPlayer('play');return false});
		},
		solution: "html,flash",
        solution: "flash,html",
        swfPath: "/assets/vendors/jplayer/dist/jplayer",
        supplied: "m4v, ogv",
        supplied: "flv",
        supplied: "m4v",
        supplied: "m4v, ogv",
        cssSelectorAncestor: "#jp_container_2",
        globalVolume: true,
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true,
	});
	
	
	/*
	//Create audio elements
	$('.play.audio').each(function(i){
		audio($(this), ++i)
	});
	
	$('.view.audio').each(function(i){
		audioView($(this), ++i)
	});
	
	//Create video elements
	$('.play.video').each(function(i){
		audio($(this), ++i)
	});
	
	$('.view.video').each(function(i){
		videoView($(this), ++i)
	});
	*/
});