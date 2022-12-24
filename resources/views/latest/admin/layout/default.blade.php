<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}" {!!Config::get('app.debug') ? 'class="debug' : ''!!}">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>
    	@section('title')
    	Admin :: Kythera family
    	@show
    </title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    
    {!! HTML::style('admin/css/bootstrap.min.css') !!}
    {!! HTML::style('admin/css/font-awesome.min.css') !!}
    {!! HTML::style('admin/css/style.css') !!}
            
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {!! HTML::script('js/ie10-viewport-bug-workaround.js') !!}
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        {!! HTML::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
        {!! HTML::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
    <![endif]-->
	<style>
		@yield('style')
	</style>
    
</head>

<body>
    <div class="container">
        <div class="navbar navbar-default" role="navigation">
            <ul class="nav navbar-nav">
                <li><a href="{!! action('Admin\AdminDashboardController@getIndex') !!}">{{ trans('admin.index') }}</a></li>
                
                <li xstyle="display:none"><a href="{!! URL::route('admin.page.index') !!}">{{ trans('admin.pages') }}</a></li>
                
                <!-- users -->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Users <span class="caret"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{!! action('Admin\AdminUserController@index') !!}">List</a></li>
                    </ul>
                </li>
                
                <!-- people -->
                <li style="display:none" class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">People <span class="caret"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="/en/admin/people/names">Names</a></li>
                        <li><a href="/en/admin/people/nicknames">Nicknames</a></li>
                        <li><a href="/en/admin/people/surnames-book">Surnames Book</a></li>
                        <li><a href="/en/admin/people/life-stories">Life Stories</a></li>
                        <li><a href="/en/admin/people/notable-kytherians">Notable Kytherians</a></li>
                        <li><a href="/en/admin/people/obituaries">Obituaries</a></li>
                    </ul>
                </li>
                
                @include('admin.layout.import')
            </ul>
            @include('admin.layout.topbar')
        </div><!-- navbar -->
    </div><!-- container -->
    
    @if(Session::has('global'))
    <div class="container">
        <!-- messages/errors -->
        <p class="bg-info">{!! Session::get('global') !!}</p>
    </div>
    @endif

    <div class="container">
        <!-- content -->
        @yield('content')
    </div>
    
    <div class="container">
        <hr>
        <footer>
        </footer>
        <span class="pull-right">&copy; 2014 Kythera-family.net, All Rights Reserved. L{!!$version!!}</span>
    </div>
    
    {!! HTML::script('admin/js/jquery-1.11.1.min.js') !!}
    {!! HTML::script('admin/js/jquery-migrate-1.2.1.min.js') !!}
    {!! HTML::script('admin/js/bootstrap.min.js') !!}
    {!! HTML::script('admin/js/jquery-ui/jquery-ui.js') !!}
    {!! HTML::script('admin/js/javascript.js') !!}
    
    <script>
        /* init javascript admin framework */
        cms.boot({"version":0.1, "debug": false, "locale": "gr"});
    </script>
  </body>
</html>
