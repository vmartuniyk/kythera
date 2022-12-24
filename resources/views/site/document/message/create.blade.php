@extends('site.layout.default')

@section('title')
	Post a message
@stop

@section('style')
@stop

@section('content')
<div class="container">
    <div class="head force-left">
      <h1 class="pull-left">Post a message</h1>
      <div class="crumb pull-right">Home > <span>Post a message</span></div>
        <br class="clear"/>
    </div>
    <hr class="thin"/>
    <div class="content entry single">
    			@if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif

                {!! Form::open(array('action'=>'DocumentMessageController@store', 'method'=>'POST', 'id'=>'entry', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
	                <div class="form-group">
                        <label class="control-label" for="entry[title]">Title</label>
                        <input class="form-control required" type="text" name="entry[title]" id="entry[title]" value="{{ Input::old('entry.title') }}"/>
                        {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="entry[content]">Message</label>
                        <textarea class="form-control ckeditor" name="entry[content]" id="entry[content]" rows="3">{{ Input::old('entry.content') }}</textarea>
                        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="entry[terms]">Terms & conditions</label>
                        <div class="checkbox">
						    <label for="entry[terms]">
						        <input class="required" type="checkbox" name="entry[terms]" id="entry[terms]" {{ Input::old('entry.terms') ? 'checked':'' }} xchecked="checked"/> I have read the <a href="{!! Router::getTermsOfUseURI() !!}">terms of use</a> and accept them.
						    </label>
						</div>
						{!! $errors->first('terms', '<span class="form-error">:message</span>') !!}
	                </div>

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right">{{ trans('locale.button.send') }}</button>
                    </div>
                {!! Form::close() !!}
    </div>
</div><!-- container -->
@stop


@section('javascript')
@if (Config::get('app.ckeditor'))
<script src="//cdn.ckeditor.com/4.4.5.1/standard/ckeditor.js"></script>
<script>
	var ckeditorCustom = {
		language: '{!!App::getLocale()!!}'
	}
	CKEDITOR.replace('entry[content]', jQuery.extend({}, ckeditorDefault, ckeditorCustom));
</script>
@endif


<script>
	jQuery(function() {
		var ruleSetCustom = {};
		jQuery("#entry").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop