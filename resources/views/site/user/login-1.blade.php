@extends('site.layout.default')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Login</div>
				<div class="panel-body">
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

					<form id="userlogin" class="form-horizontal" role="form" method="POST" action="{{ action('UsersController@postLogin') }}">
						{!! csrf_field() !!}

						<div class="form-group">
							<label class="col-md-4 control-label">Username / E-Mail</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password" id="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember" id="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Login</button>

								<a class="btn btn-link" href="{{ action('Auth\PasswordController@showResetForm') }}">Forgot Password?</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('javascript')
	<!-- Laravel Javascript Validation -->
	{!! $validator->selector('#userlogin') !!}
@endsection
