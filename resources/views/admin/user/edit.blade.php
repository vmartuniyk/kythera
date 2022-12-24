@extends('admin.layout.default')

@section('style')
@stop

@section('content')

	<div class="container">

		<h1 class="users-edit">{!! trans('admin.users') !!}</h1>

		<hr class="thin" />

		{{--
			user letters
			does not work due to? probably
			httpdocs/app/controllers/Admin/AdminUserController.php
			not getting called
			@francesdath 2017-06-07
		--}}

		{{--
		<ul class="pagination alpha">
			@foreach($letters as $letter => $n)
				<li class="{!! route('admin.users.index') !!}?letter={!! $letter !!}"><a href="?l={!! $letter !!}">{!! $letter !!}</a></li>
			@endforeach
		</ul>
		--}}

		{{--
			user search input
			@francesdath 2017-06-07
		--}}

		<form id="form-group" class="form-group admin-search">
			<input id='s' placeholder="Search &hellip;" class="form-control" name="s" type="search">
			<button type="submit" class="btn btn-default btn-black">Search</button>
		</form>

		<h2>{!! trans('admin.users.edit') !!}: {!!$item->firstname!!} {!!$item->lastname!!}</h2>

		<hr class="thin" />

		{!! Form::open(array('url'=> isset($item) ? route('admin.user.update', $item->id) :
			route('admin.user.store'),
			'method'=>isset($item) ? 'PUT' :
				'POST', 'class'=>'form-horizontal user-edit col-md-8')) !!}

			<div class="form-group">
				{!! Form::label('username', 'Username', array('class'=>'control-label')) !!}
				{!! Form::text('username', Input::old('username', isset($item) ? $item->username : null), array('class'=>'form-control')) !!}
				{!! $errors->first('username', '<span class="help-block">:message</span>') !!}
			</div>

			{{--
				added firstname lastname email
				@francesdath 2017-06-07
			--}}

			<div class="form-group">
				{!! Form::label('firstname', 'First Name', array('class'=>'control-label')) !!}
				{!! Form::text('firstname', Input::old('firstname', isset($item) ? $item->firstname : null), array('class'=>'form-control')) !!}
				{!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group">
				{!! Form::label('lastname', 'Last Name', array('class'=>'control-label')) !!}
				{!! Form::text('lastname', Input::old('lastname', isset($item) ? $item->lastname : null), array('class'=>'form-control')) !!}
				{!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group">
				{!! Form::label('email', 'Email', array('class'=>'control-label')) !!}
				{!! Form::text('email', Input::old('email', isset($item) ? $item->email : null), array('class'=>'form-control')) !!}
				{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
			</div>

			<hr class="thin" />

			<div class="form-group">
				{!! link_to(route('admin.user.index'), trans('locale.button.cancel'), array('class'=>'btn btn-default')) !!}
				{!! Form::submit(trans('locale.button.save'), array('class'=>'btn btn-black pull-right')) !!}
			</div>

		{!! Form::close() !!}

	</div>

@stop
