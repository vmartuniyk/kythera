@extends('site.layout.default-new')

@section('title')
    Add details adasd
@stop

@section('style')
/*
.list img {width:100%;}
.list label.control-label {margin-top:10px;}


.list .thumb {width:150px;margin-right:20px;border:1px solid #B6B6B6;float:left;text-align:center;height:150px;border-radius:4px;padding:2px}
.list .thumb.media {line-height: 138px;font-size: 21px;font-weight: bold;text-transform: lowercase;color:gray;padding:4px}

.list .thumb.media {background: transparent no-repeat scroll;background-size:90%;background-position: center;}
.list .thumb.media.audio {background-image: url("/assets/img/audio-black.jpg");}
.list .thumb.media.video {background-image: url("/assets/img/video-black.jpg");}
*/
@stop

@section('content')
<div class="container">
    <div class="head force-left">
      <h1 class="pull-left">Add details</h1>
      <div class="crumb pull-right">Home > <span>Add details</span></div>
        <br class="clear"/>
    </div>
    <hr class="thin"/>
    <div class="content entry single">
    			@if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif

                {!! Form::open(array('action' => 'EntryController@store', 'method' => 'POST', 'id' => 'entry', 'class' => 'form-horizontal', 'autocomplete' => 'off')) !!}
                    <div class="list">
			        @foreach($files as $i=>$file)
				        <div>


		                    <div class="details clearfix">


					        	@if ($file['type'] == 'image')
					        	<div class="thumb"><img src="{!!$file['uri']!!}" /></div>
					        	@elseif ($file['type'] == 'audio')
					        	<div class="thumb media audio">&nbsp;</div>
					        	@elseif ($file['type'] == 'video')
					        	<div class="thumb media video">&nbsp;</div>
					        	@else
					        	<div class="thumb unknown">unknown</div>
					        	@endif
					        	<input type="hidden" name="files[{!!$i!!}][f]" value="{!!$file['uri']!!}"/>
					        	<input type="hidden" name="files[{!!$i!!}][t]" value="{!!$file['type']!!}"/>

					        	<div class="pull-left">
					        	<h2>{!!$file['name']!!}</h2>
					        	</div>

		                    </div>


    						<div class="form-group">
						    <div class="category-groups clearfix">

						        <div class="col-md-4 category-group">
								    <label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][c]">Copyright owner</label>
								    <input type="text" class="form-control" name="files[{!!$i!!}][c]" id="files[{!!$i!!}][c]">
						        </div>

						        <div class="col-md-4 category-group">
						        	@if ($file['type'] == 'image')
								    <label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][d]">Date taken (dd/mm/yyyy or mm/yyyy or yyyy)</label>
						        	@elseif ($file['type'] == 'audio')
						        	<label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][d]">Date recorded (dd/mm/yyyy or mm/yyyy or yyyy)</label>
						        	@elseif ($file['type'] == 'video')
						        	<label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][d]">Date recorded (dd/mm/yyyy or mm/yyyy or yyyy)</label>
						        	@else
						        	<label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][d]">Date (dd/mm/yyyy or mm/yyyy or yyyy)</label>
						        	@endif
								    <input type="text" class="form-control xdateITA" name="files[{!!$i!!}][d]" id="files[{!!$i!!}][d]" placeholder="dd/mm/yyyy or mm/yyyy or yyyy">
   						        </div>
						    </div>
						    </div>


				        </div>
				        <hr class="blue"/>
			        @endforeach
                    </div>



                    <div class="form-group">
                        <label class="control-label" for="p">Permission</label>
                        <div class="checkbox">
						    <label for="p">
						        <input type="checkbox" class="required" name="p" id="p" {{ Input::old('p') ? 'checked':'' }} checked="checked"/> I have permission to use the provided material.
						    </label>
						</div>
						{!! $errors->first('p', '<span class="form-error">:message</span>') !!}
	                </div>

                    <div class="form-group">
                        <label class="control-label" for="t">Terms & conditions</label>
                        <div class="checkbox">
						    <label for="t">
						        <input type="checkbox" class="required" name="t" id="t" {{ Input::old('t') ? 'checked':'' }} checked="checked"/> I have read the <a href="{!!Router::getTermsOfUseURI()!!}">terms of use</a> and accept them.
						    </label>
						</div>
						{!! $errors->first('t', '<span class="form-error">:message</span>') !!}
	                </div>

					<!-- Google reCAPTCHA box -->
					<!--<div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>-->

					<!-- Google Invisible reCAPTCHA widget -->
					{{--data-badge type: bottomright, bottomleft--}}
					<div class="g-recaptcha" data-sitekey="{{ env('INVISIBLE_RECAPTCHA_SITEKEY') }}" data-badge="inline" data-size="invisible" data-callback="setResponse"></div>

					<input type="hidden" id="captcha-response" name="captcha-response" />


                    <hr class="thin"/>
                    <div class="form-group">
                    	@if (!count($files))
        				<a class="btn btn-cancel btn-default" href="javascript:history.back();">{{ trans('locale.button.back') }}</a>
        				@endif
        				
        				
        				<button type="submit" id = "entry_submit_btn" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
                        <div class="checkbox pull-right" style="margin-right:10px;">
						    <label for="l">
						        <input type="checkbox" name="l"/> Edit {!! App::getLocale() == 'en' ? 'Greek' : 'English'!!} version after update.
						    </label>
						</div>
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
			width: '897px',
	        height: '77px',
			language: '{!!App::getLocale()!!}',
			toolbar: {!!Config::get('app.debug')?'true':'false'!!}
		}
	    jQuery('textarea.ckeditor').each(function(i){
	        var id = jQuery(this).attr('id');
        	CKEDITOR.replace(id, jQuery.extend({}, ckeditorDefault, ckeditorCustom));
		});
	</script>
	@endif


<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script>
    /*
	jQuery(function() {
		var datepickerCustom = {};
    	jQuery('input.dateITA').datepicker(jQuery.extend({}, datepickerDefault, datepickerCustom));
    });
    */
</script>

<script>
	jQuery(function() {
		var ruleSetCustom = {
    		rules: {
    			p: "required",
    			t: "required"
    		},
    		messages: {
    			p: "You must have permission",
    			t: "You must accept terms of use"
    		}
		}
    	jQuery("#entry").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>

<script>

	$('#entry_submit_btn').click(function(){
		$('.error').remove();
		var recaptcha_val = $('#g-recaptcha-response').val();
		if(recaptcha_val == '' || recaptcha_val =='null' || recaptcha_val==='undefined'){
			$('#g-recaptcha-response').after('<div><small class="error" style="color: red;margin-bottom: 10px !important;;">Captcha verification is required!</small></div>');
			return false;
		}
	});

</script>

<script>
	var onloadCallback = function() {
		grecaptcha.execute();
	};

	function setResponse(response) {
		document.getElementById('captcha-response').value = response;
	}
</script>
@stop