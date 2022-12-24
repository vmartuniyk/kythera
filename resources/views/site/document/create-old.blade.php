@extends('site.layout.default')

@section('title')
	Upload your entry!
@stop

@section('style')
.ui-state-error, .ui-widget-content .ui-state-error, .ui-widget-header .ui-state-error {
    background: url("images/ui-bg_glass_95_fef1ec_1x400.png") repeat-x scroll -451px -401px #ffffff !important;
    color: #cd0a0a;
    border: none !important;
}
.plupload_message {
    height: 99% !important;
    left: 0 !important;
    position: absolute;
    top: 1px !important;
    width: 100% !important;
}
.plupload_action_icon.delete {cursor:pointer;color:red !important;font-weight:700;font-size: 30px;line-height: 17px;}
#uploader_container .ui-state-error p {color:red !important;}
.entry p.change {font-weight:700;}
.entry a {color: #00adf0;}
@stop

@section('content')
<div class="container">
    <div class="head force-left">
      <h1 class="pull-left">Add entry</h1>
      <div class="crumb pull-right">Home > <span>Add entry</span></div>
        <br class="clear"/>
    </div>
    <hr class="thin"/>
    <div class="content entry single">
    			@if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif

                <p class="change">Change to <a href="{!!action('EntriesController@create')!!}">multiple entry form</a>.</p>
                <h2>Single entry:</h2>

                {!! Form::open(array('action'=>'EntryController@next', 'method'=>'POST', 'id'=>'entry', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
					<div class="form-group">
					    <label class="control-label">Choose up to three categories for your entry</label>
					    <div class="category-groups clearfix">
					        <div class="col-md-4 category-group">
							    <label style="font-weight: 100" for="entry[cats][0]">Category 1</label>
							    <select class="form-control required category" name="entry[cats][0]" id="entry[cats][0]">
							    {{--
    							    @foreach($categories as $category)
    							     <option value="{!! $category->controller_id !!}" {!! Input::old('entry.cats.0') == $category->controller_id ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
    							    @endforeach
    							    --}}
    							    {!! xmenu::categories($categories, $selectedCatId ? $selectedCatId : Input::old('entry.cats.0'), 0) !!}
							    </select>
							    {!! $errors->first('cats', '<span class="form-error">:message</span>') !!}
					        </div>
					        <div class="col-md-4 category-group">
							    <label style="font-weight: 100" for="entry[cats][1]">Category 2</label>
							    <select class="form-control" name="entry[cats][1]" id="entry[cats][1]">
							    {{--
                                    <option value="0">-</option>
    							    @foreach($categories as $category)
    							     <option value="{!! $category->controller_id !!}" {!! Input::old('entry.cats.1') == $category->controller_id ? 'selected="selected"' : '' !!}">{!! $category->title !!}</option>
    							    @endforeach
    							    --}}
    							    {!! xmenu::categories($categories, Input::old('entry.cats.1'), 0) !!}
  							    </select>
					        </div>
					        <div class="col-md-4 category-groupx">
							    <label style="font-weight: 100" for="entry[cats][2]">Category 3</label>
							    <select class="form-control" name="entry[cats][2]" id="entry[cats][2]">
							    {{--
                                    <option value="0">-</option>
    							    @foreach($categories as $category)
    							     <option value="{!! $category->controller_id !!}" {!! Input::old('entry.cats.2') == $category->controller_id ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
    							    @endforeach
    							    --}}
    							    {!! xmenu::categories($categories, Input::old('entry.cats.2'), 0) !!}
							    </select>
					        </div>
					    </div>
					</div>

	                <div class="form-group">
                        <label class="control-label" for="entry[t]">Title</label>
                        <input class="form-control required" type="text" name="entry[title]" id="entry[title]" xrequired="required" value="{{ Input::old('entry.title') }}"/>
                        {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="entry[content]">Text/Caption</label>
                        <textarea class="form-control ckeditor" name="entry[content]" id="entry[content]" rows="3" xrequired="required">{{ Input::old('entry.content') }}</textarea>
                        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
                    </div>
                    
	                <div class="form-group feature-source" style="display:none;">
                        <label class="control-label" for="entry[source]">Source</label>
                        <input class="form-control" type="text" name="entry[source]" id="entry[source]" value="{!! Input::old('entry.source') ? Input::old('entry.source') : '' !!}"/>
                        {!! $errors->first('source', '<span class="form-error">:message</span>') !!}
                    </div>

					<!-- CONTROLLER FEATURES -->
	                <div class="form-group features DocumentQuotedTextController" style="display:none;">
                        <label class="control-label" for="entry[source]">DocumentQuotedTextController</label>
                        <input class="form-control" type="text" name="entry[source]" id="entry[source]" value="{!! Input::old('entry.source') ? Input::old('entry.source') : '' !!}"/>
                        {!! $errors->first('source', '<span class="form-error">:message</span>') !!}
                    </div>

	                <div class="form-group features DocumentLetterController" style="display:none;">
                        <label class="control-label" for="entry[source]">DocumentLetterController</label>
                        <input class="form-control" type="text" name="entry[source]" id="entry[source]" value="{!! Input::old('entry.source') ? Input::old('entry.source') : '' !!}"/>
                        {!! $errors->first('source', '<span class="form-error">:message</span>') !!}
                    </div>

                    {{--
                    <div class="form-group">
                        <label class="control-label" for="entry[l]">Language</label>
                        <select class="form-control" name="entry[l]" id="entry[l]">
                          <option value="en">English</option>
                          <option value="gr">Greek</option>
                        </select>
                    </div>
                    --}}

                    <div class="form-group">
						<div class="category-groups clearfix">
					        <div class="col-md-4 category-group">
							    <label xstyle="font-weight: 100" class="control-label" for="entry[v]">Related village</label>
							    <select class="form-control" name="entry[v]" id="entry[v]">
							    	<option value="0">-</option>
	   							    @foreach($villages as $village)
	   							    <option value="{!! $village->id !!}"  {!! Input::old('entry.v') == $village->id ? 'selected="selected"' : '' !!}>{!! $village->village_name !!}</option>
	   							    @endforeach
							    </select>
					        </div>
					        <div class="col-md-4 category-group">
					        </div>
					        <div class="col-md-4 category-group">
					        </div>
						</div>
					</div>

                    <div class="form-group">
                    	<!--
                        <label for="uploader control-label">Image/Video/Audio. Multiple files will be visible in ONE entry on the site. For multiple entries <a href="{!!action('EntriesController@create')!!}">click here</a>.</label>
                        -->
                        <label for="uploader control-label">Image/Video/Audio. For multiple entries at once, please <a href="{!!action('EntriesController@create')!!}">click here</a>.</label>
						<div id="uploader">
						    <input type="file" name="entry[images]" id="entry[images]" value="" />
						</div>
                    </div>

                    {{--
                    <!--
                    <div class="form-group">
                        <label class="control-label" for="entry[t]">Terms & conditions</label>
                        <div class="checkbox">
						    <label for="entry[t]">
						        <input type="checkbox" name="entry[t]" id="entry[t]" {{ Input::old('entry.t') ? 'checked':'' }} checked="checked"/> I have read the <a href="#">terms of use</a> and accept them.
						    </label>
						</div>
						{!! $errors->first('t', '<span class="form-error">:message</span>') !!}
	                </div>
	                -->
	                --}}

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right">{{ trans('locale.button.next') }}</button>
                    </div>
                {!! Form::close() !!}
    </div>
</div><!-- container -->
@stop


@section('javascript')

<!-- ckeditor -->
@if (Config::get('app.ckeditor'))
    @if (Config::get('app.cdn'))
    <script src="//cdn.ckeditor.com/4.4.5.1/standard/ckeditor.js"></script>
	@else
	{!! xhtml::script('assets/vendors/cdn/4.4.5.1/standard/ckeditor.js') !!}
	@endif
	<script>
		var ckeditorCustom = {
			language: '{!!App::getLocale()!!}'
		}
		CKEDITOR.replace('entry[content]', jQuery.extend({}, ckeditorDefault, ckeditorCustom));
	</script>
@endif

<!-- plupload -->
@if (Config::get('app.cdn'))
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendors/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.custom.css" type="text/css" />
    <style>.ui-state-default .ui-icon { background-image: url(/xhtml/img/ui-icons_888888_256x240.custom.png) /*{iconsDefault}*/; }</style>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/assets/vendors/plupload/js/moxie.custom.js"></script>
    <script type="text/javascript" src="/assets/vendors/plupload/js/plupload.dev.custom.js"></script>
    <script type="text/javascript" src="/assets/vendors/plupload/js/jquery.ui.plupload/jquery.ui.plupload.custom.js"></script>
@else
    {!! xhtml::style('assets/vendors/cdn/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css') !!}
    <link rel="stylesheet" href="/assets/vendors/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.custom.css" type="text/css" />
    <style>.ui-state-default .ui-icon { background-image: url(/xhtml/img/ui-icons_888888_256x240.custom.png) /*{iconsDefault}*/; }</style>
    {!! xhtml::script('assets/vendors/cdn/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js') !!}
    <script type="text/javascript" src="/assets/vendors/plupload/js/moxie.custom.js"></script>
    <script type="text/javascript" src="/assets/vendors/plupload/js/plupload.dev.custom.js"></script>
    <script type="text/javascript" src="/assets/vendors/plupload/js/jquery.ui.plupload/jquery.ui.plupload.custom.js"></script>
@endif


<script>
	jQuery(function() {
		var pluploadCustom = {
			max_file_count: 1,
			max_queue_count: 1,
			resize: false,
			filters : {
				max_file_size : '20mb',
				mime_types: [
					{title : "Image files", extensions : "{!!implode(',', Config::get('files.supported_images'))!!}"},
					{title : "Audio files", extensions : "{!!implode(',', Config::get('files.supported_audio'))!!}"},
					{title : "Video files", extensions : "{!!implode(',', Config::get('files.supported_video'))!!}"}
				],
			},
			
	 	};
    	jQuery("#uploader").plupload(jQuery.extend({}, pluploadDefault, pluploadCustom));
		jQuery("#uploader").on('complete', function(){
			$('#next').attr('disabled', false);
		});
		jQuery("#uploader").on('start', function(){
			$('#next').attr('disabled', 'disabled');
		});
    });
</script>

<script>
	jQuery(function() {
		jQuery("select.category").change(function() {

			return;
			
			var option, element, features;
			option = jQuery("select.category option:selected");
			jQuery(".features").slideUp();
			if (option.data('features').controller) {
				element = jQuery("div." + option.data('features').controller);
				jQuery(element).slideToggle();
			}
		});
    });
</script>

<script>
	jQuery(function() {
		var ruleSetCustom = {
			//debug: true,
			rules: {
				"entry[cats][0]": {required: true, min: 1}
			},
			messages: {
				"entry[cats][0]": "Please select at least one category for this entry.",
			}
		};
		jQuery("#entry").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop