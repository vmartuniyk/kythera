@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
@stop


@section('content')
<div class="container">
    <div class="head">
        <h1 class="pull-left">Newsletter subscription</h1>
        <div class="crumb pull-right">
        	{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
        </div>
    </div>
    <br class="clear"/>

    <hr class="thin"/>
    <div class="content">
    	@if (isset($confirmed))
    		@if ($confirmed)
    			<p>Newsletter subscription successful.</p>
    		@else
    			<p>Newsletter subscription failed. Please try again later.</p>
    		@endif
    	@elseif	(isset($unsubscribed))
    		@if ($unsubscribed)
    			<p>Newsletter unsubscribed.</p>
    		@else
    			<p>Newsletter unsubscription failed. Please try again later.</p>
    		@endif
    	@else
	    	<p>Dear Subscriber,
	    	<br/>You're not subscribed to the newsletter yet!
	    	<br/>
	    	<br/>We just sent you an email to {!!$subscriber->email!!} and please confirm your newsletter-subscription by clicking on the web-address provided in the email.
	    	</p>
    	@endif
    </div>
</div>
@stop


@section('javascript')
@stop