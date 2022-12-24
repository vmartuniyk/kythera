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

    <!-- fonts -->
    <script src="//use.edgefonts.net/source-sans-pro:n3,i3,n4,i4,n6,i6,n7,i7.js"></script>
    <script src="//use.edgefonts.net/source-code-pro.js"></script>


    {!! xhtml::style('admin/css/bootstrap.min.css') !!}
    {!! xhtml::style('admin/css/font-awesome.min.css') !!}
    {!! xhtml::style('admin/css/style.css') !!}
    {!! xhtml::style('assets/vendors/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css') !!}
    {!! xhtml::style('assets/vendors/DataTables/Buttons-1.5.6/css/buttons.dataTables.min.css') !!}

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    {!! xhtml::script('admin/js/ie10-viewport-bug-workaround.js') !!}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        {!! xhtml::script('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
        {!! xhtml::script('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
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
                <li><a href="{!! URL::route('admin.page.index') !!}">{{ trans('admin.pages') }}</a></li>
                <li><a href="{!! URL::route('admin.village.index') !!}">{{ trans('admin.villages') }}</a></li>
                <li><a href="{!! URL::route('admin.name.index') !!}">{{ trans('admin.names') }}</a></li>
                <li><a href="{!! action('Admin\AdminUserController@index') !!}">Users</a></li>


                <li xstyle="display:none" class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Entries <span class="caret"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="{!! action('Admin\AdminEntryController@index', array('type'=>'document')) !!}">Documents</a></li>
                        <li><a href="{!! action('Admin\AdminEntryController@index', array('type'=>'comment')) !!}">Comments</a></li>
                        <li><a href="{!! action('Admin\AdminEntryController@index', array('type'=>'guestbook')) !!}">Guestbook</a></li>
                        <!-- <li><a href="{!! action('Admin\AdminEntryController@index', array('type'=>'tree')) !!}">Family trees</a></li> -->
                        <li><a href="{!! action('Admin\AdminEntryController@index', array('type'=>'top')) !!}">Top articles</a></li>
                    </ul>
                </li>

                <li style="display:{!!Config::get('app.debug') ? 'block' : 'none'!!}"><a href="{!! action('Admin\AdminDocumentTypesController@index') !!}">Modules</a></li>
                @include('admin.layout.import')
            </ul>
            @include('admin.layout.tools')
        </div><!-- navbar -->
    </div><!-- container -->

    @if(Session::has('global'))
    <div class="container">
        <!-- messages/errors -->
        <p class="bg-info">{!! Session::get('global') !!}</p>
    </div>
    @endif

<!--     <div class="container"> -->
        <!-- content -->
        @yield('content')
        <!-- /content -->
<!--     </div> -->

    <div class="container">
        <hr>
        <footer>
          <span class="pull-right"><h4>&copy; 2016-2018 Kythera-family.net, All rights reserved. Laravel {!!$version!!}</h4></span>
        </footer>
    </div>

    {!! xhtml::script('admin/js/jquery-1.11.1.min.js') !!}
    {!! xhtml::script('admin/js/jquery-migrate-1.2.1.min.js') !!}
    {!! xhtml::script('admin/js/bootstrap.min.js') !!}
    {!! xhtml::script('admin/js/jquery-ui/jquery-ui.js') !!}
    {!! xhtml::script('admin/js/javascript.js') !!}


    <script>
        /* init javascript admin framework */
        cms.boot({"version":0.1, "debug": {!!Config::get('app.debug')?"true":"false"!!}, "locale": "{!!\App::getLocale()!!}"});
    </script>

    <!-- custom javascripts -->
  	@yield('javascript')
  	<!-- /custom javascripts -->

  </body>
</html>
