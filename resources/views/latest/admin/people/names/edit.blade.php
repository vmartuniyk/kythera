@extends('admin.layout.default')

{{-- Content --}}
@section('content')
	{{-- Edit Form --}}
    {!! Form::open(array('url'=> isset($item) ? action('admin.people.names.update', $item->id) :
                                               action('admin.people.names.store'),
                        'method'=>isset($item) ? 'PUT' :
                                                 'POST', 'class'=>'form-horizontal')) !!}
    	<div class="form-group">
        {!! Form::label('firstname', 'Firstname', array('class'=>'control-label')) !!}
        {!! Form::text('firstname', Input::old('firstname', isset($item) ? $item->firstname : null), array('class'=>'form-control')) !!}
        {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
        </div>
        
        <div class="form-group">
        {!! Form::label('lastname', 'Lastname', array('class'=>'control-label')) !!}
        {!! Form::text('lastname', Input::old('lastname', isset($item) ? $item->lastname : null), array('class'=>'form-control')) !!}
        {!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
        </div>
        
        <div class="form-group">
        {!! link_to(action('admin.people.names.index'), trans('buttons.cancel'), array('class'=>'btn btn-default')) !!}
        {!! Form::submit(trans('buttons.save'), array('class'=>'btn btn-primary')) !!}
        </div>
    {!! Form::close() !!}
@stop