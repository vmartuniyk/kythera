@extends( 'site.layout.default-new' )

@section( 'title' )
	{!! trans( 'user.personal.edit.title' ) !!}
@stop

@section( 'style' )
	.col-md-8 {
		float: none;
	}
	hr.thin.col-md-8 {
		margin-left: 0;
	}
@stop

@section( 'content' )


	<main class="page">
		<div class="inner-page">
			<div class="inner-page__container">
				<div class="inner-page__wrap">
					@include('partials.admin.left-menu')
					<div class="inner-page__content content-inner profile-page">
						<div class="content-inner__wrap">
							<section class="profile-content">
								<div class="profile-content__top profile-top">
									<h1 class="profile-top__title">{!! trans( 'user.personal.edit.title' ) !!}</h1>
									<p class="profile-top__text">
										Your profile page allows you to see entries that you’ve pinned, view Group activity and engage with the Kytherian diaspora community. View your activity below.
									</p>
									@if ( Session::has( 'message' ) )
										<div class="alert alert-info">{!! Session::get( 'message' ) !!}</div>
									@endif

									@if( Session::has( 'error' ) )
										<div class="bg-info alert alert-error alert-danger">{!! Session::get( 'error' ) !!}</div>
									@endif
								</div>
							</section>

							<div class="container">

								@if ( Session::has( 'message' ) )
									<div class="alert alert-info">{!! Session::get( 'message' ) !!}</div>
								@endif

								@if( Session::has( 'error' ) )
									<div class="bg-info alert alert-error alert-danger">{!! Session::get( 'error' ) !!}</div>
								@endif


								{!!
                                    Form::open(
                                        array(
                                            action( 'PersonalPageController@update' ),
                                            'method'=>isset($user) ? 'PUT' : 'POST',
                                            'class'=>'form-horizontal user-edit col-md-8'
                                        )
                                    )
                                !!}

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">Username</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::text( 'username', Input::old( 'username', isset( $user ) ? $user->username : null ), array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'username', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">First Name</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::text( 'firstname', Input::old( 'firstname', isset( $user ) ? $user->firstname : null ), array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'firstname', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">Last Name</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::text( 'lastname', Input::old( 'lastname', isset( $user ) ? $user->lastname : null ), array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'lastname', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">Email</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::text( 'email', Input::old( 'email', isset( $user ) ? $user->email : null), array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'email', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>


								<div class="upload-form__footer form-buttons">
									{!! link_to(action( 'PersonalPageController@edit' ), trans( 'locale.button.cancel' ), array( 'class'=>'form-buttons__submit form-btn' ) ) !!}
									{!! Form::submit( trans( 'locale.button.save' ), array( 'class'=>'form-buttons__reset form-btn' ) ) !!}
								</div>

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">Change password</div>

								</div>



								<div class="upload-form__row entry-info">
									<div class="upload-form__label">Current password</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::password( 'current_password', array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'current_password', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">New password</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::password( 'new_password', array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'new_password', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>

								<div class="upload-form__row entry-info">
									<div class="upload-form__label">Confirm password</div>
									<div class="entry-info__fields">
										<div class="entry-info__input">
											{!! Form::password( 'new_password_confirmation', array( 'class'=>'form-control' ) ) !!}
											{!! $errors->first( 'new_password_confirmation', '<span class="help-block">:message</span>' ) !!}
										</div>
									</div>
								</div>


								<div class="upload-form__footer form-buttons">
									{!! link_to(action( 'PersonalPageController@edit' ), trans( 'locale.button.cancel' ), array( 'class'=>'form-buttons__submit form-btn' ) ) !!}
									{!! Form::submit( trans( 'locale.button.save' ), array( 'class'=>'form-buttons__reset form-btn' ) ) !!}
								</div>


								{!! Form::close() !!}

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="scroll-up-btn">
			<svg stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
				<g data-name="Group 12" transform="translate(-833.866 0.693)">
					<path data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke-miterlimit="10" stroke-width="2" />
					<line data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke-miterlimit="10" stroke-width="2" />
				</g>
			</svg>
		</div>
	</main>

@stop
