@extends('admin.layout.default')

@section('content')
	{{-- Edit Form --}}
    {!! Form::open(array('url'=> isset($item) ? action('admin.user.update', $item->id) :
                                               action('admin.user.store'),
                        'method'=>isset($item) ? 'PUT' :
                                                 'POST', 'class'=>'form-horizontal')) !!}
    	<div class="form-group">
        {!! Form::label('username', 'Username', array('class'=>'control-label')) !!}
        {!! Form::text('username', Input::old('username', isset($item) ? $item->username : null), array('class'=>'form-control')) !!}
        {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
        </div>
        
        <div class="form-group">
        {!! Form::label('email', 'Email', array('class'=>'control-label')) !!}
        {!! Form::text('email', Input::old('email', isset($item) ? $item->email : null), array('class'=>'form-control')) !!}
        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        
        <div class="form-group">
        {!! link_to(action('admin.user.index'), trans('buttons.cancel'), array('class'=>'btn btn-default')) !!}
        {!! Form::submit(trans('buttons.save'), array('class'=>'btn btn-primary')) !!}
        </div>
    {!! Form::close() !!}
@stop