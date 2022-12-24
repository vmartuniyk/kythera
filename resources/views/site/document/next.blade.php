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
					        	{{--
					        	<!--
			                    <div class="pull-left">
			                        <label class="control-label" for="files[{!!$i!!}][content]">Caption</label>
			                        <textarea class="text form-control ckeditor" name="files[{!!$i!!}][content]" id="files[{!!$i!!}][content]" rows="3" xrequired="required">{{ Input::old('entry.content') }}</textarea>
			                        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
			                    </div>
			                    -->
			                    --}}
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

<div class="inner-page">
	<div class="inner-page__container">
		<div class="inner-page__wrap">
			@include('partials.admin.left-menu')
			<div class="inner-page__content content-inner">
				<div class="content-inner__wrap">
					<section class="entry-content">
						<div class="entry-content__top profile-top">
							<h1 class="profile-top__title">Entry Preview</h1>
							<p class="profile-top__text">
								You are now previewing the way your entry will look. If you are happy with it, press POST. If you’d like to change things press EDIT. Once posted, an entry cannot be edited.
							</p>
						</div>
						<div class="entry-content__card entry-card">
							<div class="entry-card__image">
								<picture>
									<source srcset="../assets/img/history.webp" type="image/webp">
									<img src="../assets/img/history.jpg?_v=1655485994518" alt="">

								</picture>
							</div>
							<div class="entry-card__footer">
								<div class="entry-card__publication">
									<div class="entry-card__date">
										<time datetime="2017-03-24">Today o’clock</time> &bull;
										<span class="entry-card__autor">{{ Auth::user()->firstname  }}</span>
									</div>
									<div class="entry-card__description">Category, Subcat 1, Subcat 2</div>
								</div>
								<div class="entry-card__activity">
									<div class="entry-card__views">
										<span>x</span> <span>Views</span>
									</div>
									<div class="entry-card__comments"><span>x</span> Comments</div>
								</div>
							</div>
						</div>
					</section>
					<section class="content-inner__text inner-main-text">
						<h1 class="inner-main-text__title">{{ $data['title'] }}</h1>
						<p class="inner-main-text__paragraf">
							{{ $data['content'] }}
						</p>
					</section>
{{--					<form action="#" method="post" class="post-entry" id="post-entry-form">--}}
						{!! Form::open(array('action' => 'EntryController@store', 'method' => 'POST', 'id' => 'entry', 'class' => 'post-entry', 'autocomplete' => 'off')) !!}
						<div class="post-entry__checkbox">
							<input type="checkbox" id="t" name="t" />
							<label for="t" class="post-entry__label">
								<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
									<path data-name="Path 120" d="M1547.375,64.333a8,8,0,1,0,8,8A8,8,0,0,0,1547.375,64.333Zm.748,11.138L1546.8,76.79a.769.769,0,0,1-1.089,0l-1.318-1.319-2.7-2.7a.769.769,0,0,1,0-1.089l.774-.774a.769.769,0,0,1,1.089,0l2.151,2.152a.771.771,0,0,0,1.089,0l4.59-4.59a.769.769,0,0,1,1.089,0l.774.774a.769.769,0,0,1,0,1.089Z" transform="translate(-1539.375 -64.333)" />
								</svg>
								I have read the <span style="margin-right: 10px"><a href="{!!Router::getTermsOfUseURI()!!}">terms of use</a> </span>  and accept them
							</label>
							{!! $errors->first('t', '<span class="form-error">:message</span>') !!}

						</div>
						<div class="post-entry__buttons">
							<button class="post-entry__edit-btn btn btn-one-color" type="submit" id="edit-entry">Edit Entry</button>
							<button class="post-entry__post-btn btn btn-two-color" type="submit" id="post-entry">Post Entry</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
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