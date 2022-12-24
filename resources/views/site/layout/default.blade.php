<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}"{!!Config::get('app.debug') ? ' class="debug"' : ''!!}>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
    	<title>
    		@section('title')
    		@show
    	</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        @section('meta_tags')
        @show
        <link rel="shortcut icon" href="/assets/img/favicon.ico">
        <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/apple-touch-icon-114x114.png">

        {!! xhtml::style('assets/css/font-awesome.min.css') !!}
        {{-- xhtml::style('assets/css/bootstrap.min.css') --}}


    @if (!Config::get('app.cdn'))
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		@else
		{!! xhtml::style('assets/vendors/cdn/bootstrap/3.3.5/css/bootstrap.min.css') !!}
		{!! xhtml::style('assets/vendors/cdn/bootstrap/3.3.5/css/bootstrap-theme.min.css') !!}
		{!! xhtml::script('assets/vendors/cdn/ajax/libs/jquery/2.1.3/jquery.min.js') !!}
		{!! xhtml::script('assets/vendors/cdn/bootstrap/3.3.5/js/bootstrap.min.js') !!}
		@endif

		<script>
		var debug = {!! Config::get('app.debug') ? 1 : 0; !!};
		var endpoint = "{!!$endpoint!!}";
		</script>

        <!--<script src="https://www.google.com/recaptcha/api.js" async defer></script>-->
        {{--INVISIBLE RECAPTCHA SCRIPTA--}}
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>



        {!! xhtml::style('assets/vendors/bootstrap-languages/languages.min.css') !!}

        {!! xhtml::style('assets/vendors/lightbox/css/lightbox.css') !!}

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        {!! xhtml::script('assets/js/ie10-viewport-bug-workaround.js') !!}

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            {!! xhtml::script('assets/js/html5shiv.min.js') !!}
            {!! xhtml::script('assets/js/respond.min.js') !!}
        <![endif]-->

        {!! xhtml::style('assets/css/style.css?cb='.time()) !!}
        {!! xhtml::style('assets/css/typeahead.css?cb='.time()) !!}

        {!! xhtml::style('assets/vendors/jplayer/dist/skin/blue.monday/css/jplayer.blue.monday.css?cb='.time()) !!}

    	<style>
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
    			#info.loading {
    			    overflow: hidden;
    			}
    			#info.loading .loader {
    			    display: block;
    			}

           @yield('style')
    	</style>
    </head>

    <body>
        @include('cookieConsent::index')

        <a href="#" class="back-to-top"></a>

        <div class="container">
            <div class="header clearfix">
                <a href="/" title="Go home">
                    <img class="pull-left" src="{!! URL::asset('assets/img/kfnlogo.png') !!}" alt="kythera family"/>
                </a>
                <img class="pull-right" src="{!! URL::asset('assets/img/janni_kat.jpg') !!}" alt="kythera family"/>
            </div>
        </div>

        <div class="container">

                {!! xmenu::controls() !!}

        </div>

        <div class="container mt10">

                {!! xmenu::header() !!}

        </div>

        <div class="container">
                <div class="thin-line"></div>
        </div>


        <div class="container mt10">

                {!! xmenu::main() !!}

        </div>

        <div class="container mt10">
                <div class="thin-line"></div>
        </div>

        <div class="container">

                {!! xmenu::sub() !!}

        </div>

        <div class="container"><hr class="line black" /></div>

        <!-- CONTENT -->
        @yield('content')
        <!-- /CONTENT -->

        <div class="container">
            <hr class="line black mt40 mt60" />
            <footer class="blue">
                <div class="row">

                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="menu">
                                <img src="{!! URL::asset('assets/img/logo-small.png') !!}" style="display: none"/>
                                {!! xmenu::footer() !!}
                            </div>
                        </div>
                        <div class="col-md-6 photo">
							@if (!Config::get('app.debug'))
								@include('site.page.footer.photos')
							@endif
                        </div>
                    </div>

                    <div class="col-md-6">

                    	{{--
                        <div class="col-md-6 post">
							@if (!Config::get('app.debug'))
								@include('site.page.footer.posts')
							@endif
                        </div>
                        --}}
                        <?php
                            //$a = array('aaa');
                            //print_r($a);
                            //print_r($items);
                        ?>
                        <div class="col-md-6 photo">
							@if (!Config::get('app.debug'))
								@include('site.page.footer.photos-2')
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
                <span class="pull-right">&copy; 2016 Kythera-family.net, All Rights Reserved.</span>
            </footer>
        </div>

        {{-- xhtml::script('assets/js/jquery.min.js') --}}

        {{-- xhtml::script('assets/js/bootstrap.min.js') --}}
        {!! xhtml::script('assets/vendors/lightbox/js/lightbox.custom.js') !!}

        {{-- xhtml::script('assets/vendors/jquery-validation-1.13.1/dist/jquery.validate.js') --}}

        {!! xhtml::script('vendor/jsvalidation/js/jsvalidation.min.js') !!}


        {!! xhtml::script('assets/vendors/typeahead.js/typeahead.bundle.custom.js?cb='.time()) !!}

        {!! xhtml::script('assets/vendors/jplayer/dist/jplayer/jquery.jplayer.min.js') !!}

        {!! xhtml::script('assets/js/javascript.js?cb='.time()) !!}



        <!-- custom stylesheets -->
		@yield('stylesheet')
		<!-- /custom stylesheets -->
        <!-- custom javascripts -->
		@yield('javascript')
		<!-- /custom javascripts -->

		<div class="loader"><!-- Place at bottom of page --></div>
    </body>
</html>
