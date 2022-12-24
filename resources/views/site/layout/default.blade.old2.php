<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" {{Config::get('app.debug') ? 'class="debug' : ''}}">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
    	<title>
    		@section('title')
    		@show
    	</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="shortcut icon" href="/assets/img/favicon.ico">
        <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/apple-touch-icon-114x114.png">

        {{ xhtml::style('assets/css/font-awesome.min.css') }}
        {{ xhtml::style('assets/css/bootstrap.min.css') }}

        {{ xhtml::style('assets/vendors/lightbox/css/lightbox.css') }}

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        {{ xhtml::script('assets/js/ie10-viewport-bug-workaround.js') }}

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            {{ xhtml::script('assets/js/html5shiv.min.js') }}
            {{ xhtml::script('assets/js/respond.min.js') }}
        <![endif]-->

        {{ xhtml::style('assets/css/style.css') }}
        {{ xhtml::style('assets/css/typeahead.css') }}
    	<style>
			/* Start by setting display:none to make this hidden.
			   Then we position it in relation to the viewport window
			   with position:fixed. Width, height, top and left speak
			   for themselves. Background we set to 80% white with
			   our animation centered, and no-repeating */
			.loader {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .8 )
			                url('/assets/img/ajax-loader.gif')
			                50% 50%
			                no-repeat;
			}

			/* When the body has the loading class, we turn
			   the scrollbar off with overflow:hidden */
			#info.loading {
			    overflow: hidden;
			}

			/* Anytime the body has the loading class, our
			   modal element will be visible */
			#info.loading .loader {
			    display: block;
			}



    	   /*chrome*/
           ul#signInDropdown button#dropdownMenu1 {padding:6px 10px}
           .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {background-color:#eee !important;}
           @yield('style')
    	</style>
    </head>

    <body>
        <a href="#" class="back-to-top"></a>

        <div class="container">
            <div class="header clearfix">
                <a href="/" title="Go home">
                    <img class="pull-left" src="{{ URL::asset('assets/img/logo.png') }}" alt="kythera family"/>
                </a>
                <img class="pull-right" src="{{ URL::asset('assets/img/header-image.png') }}" alt="kythera family"/>
            </div>
        </div>

        <div class="container">
            <nav class="header-menu">
                {{ xmenu::header() }}
            </nav>
        </div>
        <div class="container">
                {{ xmenu::main() }}

                <div class="thin-line"></div>

                {{ xmenu::login() }}

                <br class="clear" />

                {{ xmenu::sub() }}

            </div>

        <div class="container"><hr class="line black" /></div>

        <!-- CONTENT -->
        @yield('content')
        <!-- /CONTENT -->

        <div class="container">
            <hr class="line black mt40 mt60" />
            <footer>
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <img src="{{ URL::asset('assets/img/logo-small.png') }}" />
                            {{ xmenu::footer() }}
                        </div>
                        <div class="col-md-6 photo">
							@if (!Config::get('app.debug'))
								@include('site.page.footer.photos')
							@endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-6 post">
							@if (!Config::get('app.debug'))
								@include('site.page.footer.posts')
							@endif
                        </div>
                        <div class="col-md-6 social">
							@if (!Config::get('app.debug'))
								@include('site.page.footer.social')
							@endif
						</div>
                    </div>
                </div>
                <hr/>
                <span class="pull-right">&copy; 2015 Kythera-family.net, All Rights Reserved.</span>
            </footer>
        </div>

        {{ xhtml::script('assets/js/jquery.min.js') }}

        {{ xhtml::script('assets/js/bootstrap.min.js') }}
        {{ xhtml::script('assets/vendors/lightbox/js/lightbox.custom.js') }}

        {{ xhtml::script('assets/vendors/jquery-validation-1.13.1/dist/jquery.validate.js') }}

        {{ xhtml::script('assets/vendors/typeahead.js/typeahead.bundle.custom.js') }}

        {{ xhtml::script('assets/vendors/jplayer/dist/jplayer/jquery.jplayer.min.js') }}

        {{ xhtml::script('assets/js/javascript.js') }}

        <!-- custom stylesheets -->
		@yield('stylesheet')
		<!-- /custom stylesheets -->
        <!-- custom javascripts -->
		@yield('javascript')
		<!-- /custom javascripts -->

		<div class="loader"><!-- Place at bottom of page --></div>
    </body>
</html>