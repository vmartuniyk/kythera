@extends('site.layout.default')

@section('title')
	Contact guestbook author
@stop

@section('style')
.txtdoc {
    border-bottom: 1px solid #dfdfdf;
    padding: 20px 0;
}
.guestbook h3 {
    color: #000;
    font: 700 14px/14px arial;
    text-transform: uppercase;
}
.form-group {padding-left:15px;}
@stop

@section('content')
    <div class="container">

        <div class="head">
        	<h1 class="pull-left">Contact
        	{!!$entry->getFirstname()!!} {!!$entry->getLastname()!!}
        	@if (Config::get('app.debug'))
        	<br/>
        	({!!$entry->getEmail()!!})
        	({!!$entry->getCity()!!})
        	@endif
        	</h1>
        	<div class="crumb pull-right"></div>
        	<br class="clear">
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2 guestbook">
            	<div class="line"></div>

            	@if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif


            	{!! Form::open(array('action'=>'DocumentGuestbookController@send', 'method'=>'POST', 'id'=>'guestbook', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
                    <div class="form-group">
						<div class="clearfix">
					        <div class="col-md-6 category-group">

		                        <label class="control-label" for="entry[s]">Subject</label>
		                        <input class="form-control required" type="text" name="entry[s]" id="entry[s]" xrequired="required" value="{{ Input::old('entry.s') }}"/>
		                        {!! $errors->first('s', '<span class="form-error">:message</span>') !!}

					        </div>
					        <div class="col-md-6 category-group">

		                        <label class="control-label" for="entry[e]">Your email address</label>
		                        <input class="form-control required email" type="text" name="entry[e]" id="entry[e]" xrequired="required" value="{!! Auth::check() ? Auth::user()->email : Input::old('entry.e') !!}"/>
		                        {!! $errors->first('e', '<span class="form-error">:message</span>') !!}

					        </div>
						</div>
					</div>

            	    <div class="form-group">
                        <label class="control-label" for="entry[content]">Message</label>
                        <textarea class="form-control required" name="entry[content]" id="entry[content]" rows="3" xrequired="required"></textarea>
                        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                    </div>

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right">{{ trans('locale.button.send') }}</button>
                    </div>
            	{!! Form::close() !!}

            </div>

            <div class="col-md-4 sidebar">
            {{--
                @if (0&&!Config::get('app.debug'))
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