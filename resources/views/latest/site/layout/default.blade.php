<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}" {!!Config::get('app.debug') ? 'class="debug' : ''!!}">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
	<title>
		@section('title')
		Kythera family
		@show
	</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{!! URL::asset('css/bootstrap.min.css') !!}" />
    <link xhref="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="{!! URL::asset('css/style.css') !!}" />
    <link rel="stylesheet" href="/xhtml/css/style.css" />
            
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{!! URL::asset('js/ie10-viewport-bug-workaround.js') !!}"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<style>
		@yield('style')
	</style>
    
</head>
<body>
    <div class="container">
        <div class="navbar navbar-default" role="navigation">
            {{--
            <ul class="nav navbar-nav" xstyle="display:none">
                <li><a href="{!! action('IndexController@index') !!}">{!! Lang::get('locale.index') !!}</a></li>
                <li><a href="{!! action('IndexController@about') !!}">{!! Lang::get('locale.about') !!}</a></li>
            </ul>
            --}}
            @include('site.layout.menu')
            @include('site.layout.social')
        </div><!-- navbar -->
    </div><!-- container -->

    @if(Session::has('global'))
    <p class="bg-info">{!! Session::get('global') !!}</p>
    @endif
    
    <div class="container">
        @yield('content')
    </div>
    
    <div class="container">
        <hr>
        <footer>
        </footer>
        <span class="pull-right">&copy; 2014 Kythera-family.net, All Rights Reserved.</span>
    </div>
    
	<script src="{!! URL::asset('js/jquery.js') !!}"></script>
    <script src="{!! URL::asset('js/bootstrap.min.js') !!}"></script>
    <script src="{!! URL::asset('js/typeahead.bundle.min.js') !!}"></script>
    <script xsrc="{!! URL::asset('js/javascript.js') !!}"></script>
</body>
</html>