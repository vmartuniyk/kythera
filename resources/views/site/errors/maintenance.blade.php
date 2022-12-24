<!DOCTYPE html>
<html lang="{!! App::getLocale() !!}">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
    	<title>
            Site maintenance
    	</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link rel="shortcut icon" href="/assets/img/favicon.ico">
        <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/apple-touch-icon-114x114.png">

        {!! xhtml::style('assets/css/font-awesome.min.css') !!}


        @if (Config::get('app.cdn'))
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		@else
		{!! xhtml::style('assets/vendors/cdn/bootstrap/3.3.5/css/bootstrap.min.css') !!}
		{!! xhtml::style('assets/vendors/cdn/bootstrap/3.3.5/css/bootstrap-theme.min.css') !!}
		{!! xhtml::script('assets/vendors/cdn/ajax/libs/jquery/1.11.3/jquery.min.js') !!}
		{!! xhtml::script('assets/vendors/cdn/bootstrap/3.3.5/js/bootstrap.min.js') !!}
		@endif


        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        {!! xhtml::script('assets/js/ie10-viewport-bug-workaround.js') !!}

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            {!! xhtml::script('assets/js/html5shiv.min.js') !!}
            {!! xhtml::script('assets/js/respond.min.js') !!}
        <![endif]-->

        {!! xhtml::style('assets/css/style.css?cb='.time()) !!}
    </head>

    <body>
        <a href="#" class="back-to-top"></a>

        <div class="container">
            <div class="header clearfix">
                <a href="/" title="Go home">
                    <img class="pull-left" src="{!! URL::asset('assets/img/kfnlogo.png') !!}" alt="kythera family"/>
                </a>
                <img class="pull-right" src="{!! URL::asset('assets/img/janni_kat.jpg') !!}" alt="kythera family"/>
            </div>
        </div>

        <div class="container"><hr class="line black" /></div>

        <div class="container">
            <h1>Site maintenance</h1>
            <p>
                Were doing site maintenance, please check back later.
                <br/>Thank you for your patience.
                <br/>
                <br/>Direct questions to {!!\Illuminate\Support\Facades\Config::get('app.administrator')!!}
            </p>
        </div>

        <div class="container">
            <hr class="line black" />
            <span class="pull-right">&copy; 2016 Kythera-family.net, All Rights Reserved.</span>
        </div>

    </body>
</html>