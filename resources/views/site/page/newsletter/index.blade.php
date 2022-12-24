@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
@stop


@section('content')
<div class="container">
    <div class="head">
        <h1 class="pull-left">{{ $page->title }}</h1>
        <div class="crumb pull-right">
        	{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
        </div>
    </div>
    <br class="clear"/>

    <hr class="thin"/>
    <div class="content">
	    @if(!empty($page->content))
	    <p>{!!$page->content!!}</p>
	    <br/>
	    <div class="line"></div>
	    @endif
    </div>
</div>

<div class="container">
	@if (isset($subscribed) && $subscribed)
		<p>Your subscribed to the newsletter.</p>
	@else
		@if (Auth::check())
			<p>
			Dear {!! xhtml::fullname(Auth::user(), false) !!},
			<br/>
			<br/>
            @if (isset($subscriber) && $subscriber->enabled)
            	you are already listed as a subscriber to the kythera-family.net newsletter. If you wish to unsubscribe please click <a href="{!!action('NewsLetterController@getUnsubscribe', array('email'=>Auth::user()->email, 'token'=>\Kythera\Models\Subscriber::getToken(Auth::user()->email)))!!}">here</a>.
            @else
            	please click <a href="{!!action('NewsLetterController@getSubscribe', array('email'=>Auth::user()->email))!!}">here</a> to subscribe to the newsletter.
            @endif
            </p>
		@else
	    {!! Form::open(array('action'=>'NewsLetterController@postSubscribe', 'method'=>'POST', 'id'=>'form', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
	        <fieldset>
	            <div class="form-group">
	                <label for="firstname">{!! Lang::get('locale.form.firstname') !!}</label>
	                <input class="form-control required" tabindex="1" placeholder="" type="text" name="firstname" id="firstname" value="{{ Input::old('firstname') }}">
	                {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
	            </div>

	            <div class="form-group">
	                <label for="lastname">{!! Lang::get('locale.form.lastname') !!}</label>
	                <input class="form-control required" tabindex="1" placeholder="" type="text" name="lastname" id="lastname" value="{{ Input::old('lastname') }}">
	                {!! $errors->first('lastname', '<span class="help-block">:message</span>') !!}
	            </div>

	            <div class="form-group">
	                <label for="email">{!! Lang::get('locale.form.email') !!}</label>
	                <input class="form-control required" tabindex="1" placeholder="" type="email" name="email" id="email" value="{{ Input::old('email') }}">
	                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
	            </div>

	            <div class="form-group pull-right">
	                <button tabindex="4" type="submit" class="btn btn-black">{{ Lang::get('locale.button.subscribe') }}</button>
	            </div>
	        </fieldset>
	    {!! Form::close() !!}
		@endif
    @endif
</div>
@stop


@section('javascript')
<script>
	jQuery(function() {
		var ruleSetCustom = {};
		jQuery("#form").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop