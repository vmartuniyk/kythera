@extends('site.layout.default')

@section('title')
    Edit entry: {!!$entry->title!!}
@stop

@section('style')
.plupload_action_icon.delete {cursor:pointer;color:red !important;font-weight:700;font-size: 30px;line-height: 17px;}
.form-error{display: block;margin-bottom: 10px;margin-top: 5px;}
@stop

@section('content')
<div class="container">
    <div class="head force-left">
      <h1 class="pull-left">Edit entry: {!!$entry->title!!}</h1>
      <div class="crumb pull-right">Home > <span>Edit entry</span></div>
      <br class="clear"/>
    </div>
    <hr class="thin"/>
    <div class="content entry single">
				{{--
                <p>Change to <a href="{!!action('EntriesController@create')!!}">multiple entry form</a>.</p>
                <h2>Single entry:</h2>
                --}}

                @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p><br/>@endif
                
                @if (App::getLocale() == 'en')
                    <a href="/gr/entry/{!!$entry->id!!}/edit" class="btn btn-black pull-right">Edit Greek version</a>
                @else
                    <a href="/en/entry/{!!$entry->id!!}/edit" class="btn btn-black pull-right">Edit English version</a>
                @endif
                

                {!! Form::open(array('action' => array('EntryController@next', $entry->id), 'method' => 'POST', 'id' => 'entry', 'class' => 'form-horizontal')) !!}
					<div class="form-group">
					    <label class="control-label">Choose up to three categories for your entry</label>
					    <div class="category-groups clearfix">
					        <div class="col-md-4 category-group">
							    <label style="font-weight: 100" for="entry[cats][0]">Category 1</label>
							    <select class="form-control required category" name="entry[cats][0]" id="entry[cats][0]">
							    {{--
    							    @foreach($categories as $category)
    							     <option value="{!! $category->controller_id !!}" {!! $category->controller_id == $cats[0] ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
    							    @endforeach
    							    --}}
    							    {!! xmenu::categories($categories, $cats[0]) !!}
							    </select>
					        </div>
					        <div class="col-md-4 category-group">
							    <label style="font-weight: 100" for="entry[cats][1]">Category 2</label>
							    <select class="form-control" name="entry[cats][1]" id="entry[cats][1]">
							    {{--
                                    <option value="0">-</option>
    							    @foreach($categories as $category)
    							     <option value="{!! $category->controller_id !!}" {!! $category->controller_id == $cats[1] ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
    							    @endforeach
    							    --}}
    							    {!! xmenu::categories($categories, $cats[1], 0) !!}
  							    </select>
					        </div>
					        <div class="col-md-4 category-groupx">
							    <label style="font-weight: 100" for="entry[cats][2]">Category 3</label>
							    <select class="form-control" name="entry[cats][2]" id="entry[cats][2]">
							    {{--
                                    <option value="0">-</option>
    							    @foreach($categories as $category)
    							     <option value="{!! $category->controller_id !!}" {!! $category->controller_id == $cats[2] ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
    							    @endforeach
    							    --}}
    							    {!! xmenu::categories($categories, $cats[2], 0) !!}
							    </select>
					        </div>
					    </div>
					</div>

	                <div class="form-group">
                        <label class="control-label" for="entry[t]">Title</label>
                        <input class="form-control required" type="text" name="entry[title]" id="entry[title]" xrequired="required" value="{!! Input::old('entry.title') ? Input::old('entry.title') : $entry->title !!}"/>
                        {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="entry[content]">Text/Caption</label>
                        <textarea class="form-control ckeditor" name="entry[content]" id="entry[content]" rows="3" xrequired="required">{{ Input::old('entry.content') ? Input::old('entry.content') : $entry->content }}</textarea>
                        {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
                    </div>

                    @if (isset($entry->source))
	                <div class="form-group feature-source" xstyle="display:none;">
                        <label class="control-label" for="entry[source]">Source</label>
                        <input class="form-control" type="text" name="entry[source]" id="entry[source]" value="{!! Input::old('entry.source') ? Input::old('entry.source') : $entry->source !!}"/>
                        {!! $errors->first('source', '<span class="form-error">:message</span>') !!}
                    </div>
                    @endif

                    <div class="form-group">
						<div class="category-groups clearfix">
					        <div class="col-md-4 category-group">
							    <label xstyle="font-weight: 100" class="control-label" for="entry[v]">Related village</label>
							    <select class="form-control" name="entry[v]" id="entry[v]">
							    	<option value="0">-</option>
	   							    @foreach($villages as $village)
	   							    	<option value="{!! $village->id !!}" {!! $entry->related_village_id == $village->id ? 'selected="selected"' : '' !!}>{!! $village->village_name !!}</option>
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
                        <label for="uploader control-label">Image/Video/Audio</label>
						<div id="uploader">
						    <input type="file" name="entry[images]" id="entry[images]" value="" />
						</div>
                    </div>


                    {{--
					<!--
                    <div class="form-group">
                        <label class="control-label" for="entry[l]">Language</label>
                        <select class="form-control" name="entry[l]" id="entry[l]">
                          <option value="en">English</option>
                          <option value="gr">Greek</option>
                        </select>
                    </div>
                    -->
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
        				<button type="submit" class="btn btn-black pull-right">{{ trans('locale.button.next') }}</button>
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
			language: '{!!App::getLocale()!!}',
			toolbar: {!!Config::get('app.debug')?'true':'false'!!}
		}
		CKEDITOR.replace('entry[content]', jQuery.extend({}, ckeditorDefault, ckeditorCustom));
	</script>
	@endif

<!-- plupload -->
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="/assets/vendors/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.custom.css" type="text/css" />
<style>.ui-state-default .ui-icon { background-image: url(/xhtml/img/ui-icons_888888_256x240.custom.png) /*{iconsDefault}*/; }</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/assets/vendors/plupload/js/moxie.custom.js"></script>
<script type="text/javascript" src="/assets/vendors/plupload/js/plupload.dev.custom.js"></script>
<script type="text/javascript" src="/assets/vendors/plupload/js/jquery.ui.plupload/jquery.ui.plupload.custom.js"></script>
<script>
	jQuery(function() {
		var pluploadSingle = {
				max_file_count: 1,
				max_queue_count: 1,
				resize: false,
				filters : {
					max_file_size : '20mb',
					mime_types: [
						{title : "Image files", extensions : "{!!implode(',', Config::get('files.supported_images'))!!}"},
						{title : "Audio files", extensions : "{!!implode(',', Config::get('files.supported_audio'))!!}"},
						{title : "Video files", extensions : "{!!implode(',', Config::get('files.supported_video'))!!}"}
					]
				}
		 	};
	    jQuery("#uploader").plupload(jQuery.extend({}, pluploadDefault, pluploadSingle));
    	
		setTimeout(function() {
			jQuery('#uploader').plupload('addFiles', {!! json_encode($files) !!});
		}, 1000);
    });
</script>

<script>
	jQuery(function() {
		jQuery("select.category").change(function() {
			var option, element;
			option  = jQuery("select.category option:selected");
			element = jQuery("div.feature-source");
			option.data('features').source ? $(element).fadeIn() : $(element).fadeOut();
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