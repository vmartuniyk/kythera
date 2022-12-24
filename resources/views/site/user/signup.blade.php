@extends('site.layout.default')

@section('title')
	Register
@endsection

@section('content')
<div class="container access">
    <h2>Register</h2>

		@if(Session::has('global'))
      <p class="bg-info">{!! Session::get('global') !!}</p>
    @endif

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

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


    <form id="register" method="POST" action="{{ action('UsersController@postRegister') }}" accept-charset="UTF-8">
        {!! csrf_field() !!}
        <fieldset>
            <div class="form-group">
                <label for="firstname">Firstname</label>
                <input class="form-control required" placeholder="Firstname" type="text" name="firstname" id="firstname" value="{{ Input::old('firstname') }}">
            </div>
            <div class="form-group">
                <label for="lastname">Surname</label>
                <input class="form-control required" placeholder="Surname" type="text" name="lastname" id="lastname" value="{{ Input::old('lastname') }}">
            </div>
            <div class="form-group">
                <label for="email">Email Address <small>(Confirmation required)</small></label>
                <input class="form-control required" placeholder="example@gmail.com" type="email" name="email" id="email" value="{{ Input::old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control required" placeholder="Password" type="password" name="password" id="password2">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input class="form-control required" placeholder="Password" type="password" name="password_confirmation" id="password_confirmation">
            </div>

						<div class="form-group">
				        <label xstyle="font-weight: 100" class="control-label">Captcha</label>
				        {!! app('captcha')->render(); !!}
	    	    </div>

            <div class="form-actions form-group">
              <button type="submit" class="btn btn-black">Register</button>
            </div>
        </fieldset>
    </form>
</div>
@endsection

@section('javascript')
<!-- Laravel Javascript Validation -->
{!! $validator->selector('#register') !!}

<script>
  $(document).ready(function() {
      //g-recaptcha invisible
      if($('#_g-recaptcha').length > 0){
          _beforeSubmit = function() {
              if($('#register').valid())
                  return true;

              return false;
          }
      }
  });
</script>
@endsection
