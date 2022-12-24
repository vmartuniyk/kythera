@extends('site.layout.default-new')

@section('content')
		<div class="main-login" style="background-color: #0D3F7C;">
			<div class="inner-section__container" style="text-align: center;padding: 40px">
				<div class="container">
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<div class="panel panel-default">
								<div class="log-menu__logo">
									<img src="{{ URL::asset('assets/img/icons/log-menu-logo.svg') }}" alt="">
								</div>
								<div class="panel-heading" style="margin-bottom: 10px;">Login</div>
								<div class="panel-body" style="max-width: 300px;margin: 0 auto;margin-bottom: 10px;">
									@if (session('status'))
										<div class="alert alert-success" style="color: white;padding: 10px;">
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

										<form action="{{ action('UsersController@postLogin') }}" method="POST" class="log-menu__form" style="max-width: 300px;margin: 0 auto">
											{!! csrf_field() !!}
											<div class="log-menu__input">
												<input type="text" placeholder="Enter Username" name="email" id="email" value="{{ old('email') }}">
											</div>
											<div class="log-menu__input">
												<input type="password" placeholder="Enter Password" name="password" id="password">
											</div>
											<div class="log-menu__buttons">
												<button type="submit" class="log-menu__login form-btn">
													<svg data-name="Group 196" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="32" height="23.691" viewBox="0 0 32 23.691">
														<path data-name="Path 155" d="M-1253.484,390.573a6.943,6.943,0,0,1,2.29.374,2.652,2.652,0,0,0,.379-1.365,2.669,2.669,0,0,0-2.669-2.669,2.669,2.669,0,0,0-2.668,2.669,2.653,2.653,0,0,0,.379,1.365A6.943,6.943,0,0,1-1253.484,390.573Z" transform="translate(1269.484 -374.802)" />
														<g data-name="Group 168" transform="translate(0 0)">
															<path data-name="Path 156" d="M-1153.829,218.925l0,0a14.927,14.927,0,0,0-2.344,3.6,19.219,19.219,0,0,0-1.417,4.147c0,.01-.007.02-.009.03a4.324,4.324,0,0,1,1.185,1.241c.32-.372.659-.742,1.026-1.113a22.369,22.369,0,0,1,2.314-2.046,18.106,18.106,0,0,1,2.63-1.695,12.574,12.574,0,0,1,1.96-.829,4.057,4.057,0,0,0-4.028-3.576,4.043,4.043,0,0,0-1.286.209Z" transform="translate(1176.06 -215.322)" />
															<path data-name="Path 157" d="M-1425.391,223.089a18.109,18.109,0,0,1,2.63,1.695,22.366,22.366,0,0,1,2.314,2.046c.366.372.706.742,1.026,1.113a4.322,4.322,0,0,1,1.189-1.243c0-.008-.005-.015-.007-.023a19.251,19.251,0,0,0-1.421-4.152,14.922,14.922,0,0,0-2.347-3.6c-.009-.01-.016-.022-.024-.033a4.044,4.044,0,0,0-1.292-.211,4.057,4.057,0,0,0-4.028,3.576A12.576,12.576,0,0,1-1425.391,223.089Z" transform="translate(1431.78 -215.322)" />
															<path data-name="Path 158" d="M-1289.284,160.467a21.02,21.02,0,0,1,1.284,4.257,4.318,4.318,0,0,1,.932-.1,4.317,4.317,0,0,1,.927.1,21.015,21.015,0,0,1,1.286-4.256,16.942,16.942,0,0,1,2.3-3.947,5.306,5.306,0,0,0-4.511-2.509,5.306,5.306,0,0,0-4.511,2.509A16.925,16.925,0,0,1-1289.284,160.467Z" transform="translate(1303.068 -154.011)" />
															<path data-name="Path 159" d="M-1500.819,327.345a4.365,4.365,0,0,1,.029-.5,19.605,19.605,0,0,0-1.59-1.687,20.546,20.546,0,0,0-2.2-1.808,16.393,16.393,0,0,0-2.409-1.435,9.606,9.606,0,0,0-2.352-.8,3.335,3.335,0,0,0-3.176,3.329c0,.01,0,.02,0,.03a2.682,2.682,0,0,0,2.508,2.6,12.045,12.045,0,0,1,6.024,2.108,3.057,3.057,0,0,0,1.781.535,3.611,3.611,0,0,0,1.831-.47A4.287,4.287,0,0,1-1500.819,327.345Z" transform="translate(1512.516 -312.431)" />
															<path data-name="Path 160" d="M-1121.71,321.121a9.609,9.609,0,0,0-2.352.8,16.423,16.423,0,0,0-2.409,1.435,20.552,20.552,0,0,0-2.2,1.808,19.635,19.635,0,0,0-1.59,1.687,4.337,4.337,0,0,1,.029.5,4.282,4.282,0,0,1-.447,1.91,3.612,3.612,0,0,0,1.831.47,3.057,3.057,0,0,0,1.781-.535,12.045,12.045,0,0,1,6.023-2.108,2.682,2.682,0,0,0,2.508-2.6c0-.01,0-.02,0-.03A3.335,3.335,0,0,0-1121.71,321.121Z" transform="translate(1150.533 -312.431)" />
														</g>
														<path data-name="Path 161" d="M-1370.655,493.051c-4.03,0-4.884,2.762-9.1-.286a9.637,9.637,0,0,0,9.1,6.075,9.864,9.864,0,0,0,9.1-6.075C-1365.771,495.813-1366.625,493.051-1370.655,493.051Z" transform="translate(1386.655 -475.149)" />
													</svg>
													<span>Login</span>
												</button>
												<button type="button" class="log-menu__forget">Forget Password?</button>
											</div>
										</form>
								</div>
							</div>
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
