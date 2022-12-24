@extends('site.layout.default')

@section('title')
	Contact author
@stop

@section('style')
.form-group {padding-left:15px;}

.content .entries .stats {margin:0;font: 12px/12px arial;font-weight:700}
.content .entries ul {list-style:none; padding-left:0}
.content .entries ul.entries li div:first-child {padding:10px 0}
.content .entries ul.entries li div.actions {width:200px;padding:10px 0}
.content .entries ul.entries li a.enable {color:orange}
.content .entries ul.entries li span.date {xcolor: #cccccc;font: 12px/12px arial;}
.content .entries ul.entries li hr.thin {margin:0}
.content .entries ul.entries li.hover {background-color: #efefef;}
.content .entries ul.entries li .category p {margin-left:0;}
@stop

@section('content')
    <div class="container">
        @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif
    
		@if (!isset($user))
			@if($global)<p class="bg-info">{!! $global !!}</p>@endif
		@else
	        <div class="head">
	        	<h1 class="pull-left">Contact
	        	{!! xmenu::fullname($user) !!}
	        	@if (Config::get('app.debug'))
	        	<br/>
	        	({!!mb_strtolower($user->email)!!})
	        	@endif
	        	</h1>
	        	<div class="crumb pull-right"></div>
	        	<br class="clear">
	        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2 entries">
            	<div class="line"></div>

            	{!! Form::open(array('action'=>'UsersController@postContact', 'method'=>'POST', 'id'=>'contact', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
                    <div class="form-group">
						<div class="clearfix">
					        <div class="col-md-6 category-group">

		                        <label class="control-label" for="entry[s]">Subject</label>
		                        <input class="form-control required" type="text" name="entry[s]" id="entry[s]" value="{{ Input::old('entry.s') }}"/>
		                        {!! $errors->first('s', '<span class="form-error">:message</span>') !!}

					        </div>
					        <div class="col-md-6 category-group">

		                        <label class="control-label" for="entry[e]">Your email address</label>
		                        <input class="form-control required email" type="text" name="entry[e]" id="entry[e]" value="{!! Auth::check() ? Auth::user()->email : Input::old('entry.e') !!}"/>
		                        {!! $errors->first('e', '<span class="form-error">:message</span>') !!}

					        </div>
						</div>
					</div>

            	    <div class="form-group">
                        <label class="control-label" for="entry[content]">Message</label>
                        <textarea class="form-control required" name="entry[content]" id="entry[content]" rows="3"></textarea>
                        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                    </div>

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right">{{ trans('locale.button.send') }}</button>
                    </div>
            	{!! Form::close() !!}
            	
            	
            	
                <!-- user entries -->
                @include('site.page.user.entries')
                <!-- /user entries -->
            	
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
        @endif

    </div>
@stop

@section('javascript')
<script>
	jQuery(function() {
		var ruleSetCustom = {};
		jQuery("#contact").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop