<ul class="nav navbar-nav navbar-right">
     <li style="display:none">
        <form id="remote" class="navbar-search navbar-form" method="GET" action="">
            <input type="text" class="typeahead form-control" placeholder="{!! Lang::get('locale.search:articles') !!}..." name="s">
        </form>
    </li>
    <li>
        {!! Form::open(['action' => 'LanguageController@postLanguage', 'class'=>'navbar-search navbar-form']) !!}
        {!! Form::select('locale', ['en'=>Lang::get('locale.language.english'),'gr'=>Lang::get('locale.language.greek')], App::getLocale(), ['onchange'=>'submit()', 'class'=>'form-control']) !!}
        {!! Form::hidden('uri', Request::path()) !!}
        {!! Form::close() !!}
    </li>
    <li><a href="{!! action('Admin\AdminDashboardController@getIndex') !!}">{{ trans('admin.index') }}</a>

    <li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        @if(Auth::check())
            {!!trans('locale.welcome')!!} <span class="blue">{{ Auth::user()->full_name }}</span>
        @else
            {!!trans('locale.user')!!}
        @endif
        <span class="caret"></span></a>

        @if(Auth::check())
            <ul role="menu" class="dropdown-menu">
                <li><a href="{{ action('Admin\AdminDashboardController@getIndex') }}">{!!trans('locale.control.panel')!!}</a></li>
                <li class="divider"></li>
                <li><a href="{{ action('UsersController@getLogout') }}">{!!trans('locale.logout')!!}</a></li>
            </ul>
        @else
            <ul role="menu" class="dropdown-menu">
                <li><a href="{{ action('UsersController@getLogin') }}">{!!trans('locale.login')!!}</a></li>
                <li><a href="{{ action('UsersController@getCreate') }}">{!!trans('locale.register')!!}</a></li>
                <li><a href="{{ action('UsersController@getForgot') }}">{!!trans('locale.forgot-password')!!}</a></li>
            </ul>
        @endif

    </li>
</ul>
