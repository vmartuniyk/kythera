<ul class="nav navbar-nav navbar-right">
     <li style="display:none">
        <form id="remote" class="navbar-search navbar-form" method="GET" action="">
            <input type="text" class="typeahead form-control" placeholder="{!! Lang::get('locale.search:articles') !!}..." name="s">
        </form>
    </li>
    <li xstyle="display:none">
        {!! Form::open(['action' => 'LanguageController@postLanguage', 'class'=>'navbar-search navbar-form']) !!}
        {!! Form::select('locale', ['en'=>Lang::get('locale.language.english'),'gr'=>Lang::get('locale.language.greek')], App::getLocale(), ['onchange'=>'submit()', 'class'=>'form-control']) !!}
        {!! Form::hidden('uri', Request::path()) !!}
        {!! Form::close() !!}
    </li>
    <li style="display:none"><a href="{{-- URL::route('site.index') --}}">{{ trans('site.index') }}</a></li>
    <li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        @if(Auth::check())
            Welcome <span class="blue">{{ Auth::user()->full_name }}</span>
        @else
            User
        @endif
        <span class="caret"></span></a>

        @if(Auth::check())
            <ul role="menu" class="dropdown-menu">
                <li><a href="{{ action('UsersController@getLogout') }}">Logout</a></li>
            </ul>
        @else
            <ul role="menu" class="dropdown-menu">
                <li><a href="{{ action('UsersController@getLogin') }}">Login</a></li>
                <li><a href="{{ action('UsersController@getCreate') }}">Register</a></li>
                <li><a href="{{ action('UsersController@getForgot') }}">Forgot password</a></li>
            </ul>
        @endif

    </li>
</ul>
