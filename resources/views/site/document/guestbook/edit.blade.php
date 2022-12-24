@extends('site.layout.default')

@section('title')
	Edit guestbook entry
@stop

@section('style')
.form-group {padding-left:15px}
@stop

@section('content')
    <div class="container">

        <div class="head">
        	<h1 class="pull-left">Edit guestbook entry</h1>
        	<div class="crumb pull-right"></div>
        	<br class="clear">
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 xcol2 xguestbook">
            	<div class="line"></div>

            	@if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif

            	{!! Form::open(array('action'=>array('DocumentGuestbookController@update', $entry->id), 'method'=>'PUT', 'id'=>'guestbook', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
                    <div class="form-group">
						<div class="clearfix">
					        <div class="col-md-6 category-group">

		                        <label class="control-label" for="firstname">Firstname</label>
		                        <input class="form-control required" type="text" name="firstname" id="firstname" xrequired="required" value="{{ Input::old('firstname') ? Input::old('firstname') : $entry->getFirstname() }}"/>
		                        {!! $errors->first('firstname', '<span class="form-error">:message</span>') !!}


		                        <label class="control-label" for="surname">Surname</label>
		                        <input class="form-control required" type="text" name="surname" id="surname" xrequired="required" value="{{ Input::old('surname') ? Input::old('surname') : $entry->getLastname() }}"/>
		                        {!! $errors->first('surname', '<span class="form-error">:message</span>') !!}


					        </div>
					        <div class="col-md-6 category-group">

		                        <label class="control-label" for="email">Email</label>
		                        <input class="form-control required email" type="text" name="email" id="email" xrequired="required" value="{{ Input::old('email') ? Input::old('email') : $entry->getEmail() }}"/>
		                        {!! $errors->first('email', '<span class="form-error">:message</span>') !!}


		                        <label class="control-label" for="city">City/Country</label>
		                        <input class="form-control required" type="text" name="city" id="city" xrequired="required" value="{{ Input::old('city') ? Input::old('city') : $entry->getCity() }}"/>
		                        {!! $errors->first('city', '<span class="form-error">:message</span>') !!}

					        </div>
						</div>
					</div>

            	    <div class="form-group">
                        <label class="control-label" for="message_content">Message</label>
                        <textarea class="form-control required" name="message_content" id="message_content" rows="3" xrequired="required">{{ Input::old('message_content') ? Input::old('message_content') : $entry->content }}</textarea>
                        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
						<div class="category-groups clearfix">
					        <div class="col-md-4 category-group">
							    <label xstyle="font-weight: 100" class="control-label" for="village_id">Family Village Name</label>
							    <select class="form-control" name="village_id" id="village_id">
							    	<option value="0">-</option>
	   							    @foreach($villages as $village)
	   							    <option value="{!! $village->id !!}"  {!! $entry->related_village_id == $village->id ? 'selected="selected"' : '' !!}>{!! $village->village_name !!}</option>
	   							    @endforeach
							    </select>
					        </div>
					        <div class="col-md-4 category-group">
					        </div>
					        <div class="col-md-4 category-group">
					        </div>
						</div>
					</div>

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
                    </div>
            	{!! Form::close() !!}


            </div>

            <div class="col-md-4 sidebar">
            {{--
                @if (!Config::get('app.debug'))
                    <!-- sidebar -->
                    @include('site.document.text.blocks.sidebar')
                    <!-- /sidebar -->
                @endif
                --}}
            </div>

            <!-- /content -->
        </div>

    </div>
@stop


@section('javascript')
<script>
	jQuery(function() {
		var ruleSetCustom = {};
		jQuery("#guestbook").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop
