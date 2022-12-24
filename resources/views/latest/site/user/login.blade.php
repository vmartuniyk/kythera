@extends('site.layout.default')

@section('title')
@stop

@section('style')
@stop

@section('content')
<h1>LOGIN</h1>

@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<form method="POST" action="{{ action('UsersController@postLogin') }}" accept-charset="UTF-8" role="form">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <fieldset>
        <div class="form-group">
            <label for="email">Username or Email</label>
            <input class="form-control" tabindex="1" placeholder='example@gmail.com' type="text" name="email" id="email" value="{{ Input::old('email') }}">
        </div>
        <div class="form-group">
            <label for="password">
                Password
                <small>
                    <a href="{{ url('/users/forgot') }}">Forgot Your Password?</a>
                </small>
            </label>
            <input class="form-control" tabindex="2" placeholder="Password" type="password" name="password" id="password">
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label for="remember">
                    <input type="hidden" name="remember" value="0">
                    <input tabindex="4" type="checkbox" name="remember" id="remember" value="1"> Remember me
                </label>
            </div>
        </div>

        <div class="form-group">
            <button tabindex="3" type="submit" class="btn btn-default">Login</button>
        </div>
    </fieldset>
</form>
@stop
