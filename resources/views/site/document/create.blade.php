@extends('site.layout.default-new')

@section('title')
	Upload your new entry!
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
	<main class="page">
		<div class="inner-page">
			<div class="inner-page__container">
				<div class="inner-page__wrap">
					@include('partials.admin.left-menu')
					<div class="inner-page__content content-inner">
						<div class="content-inner__wrap">
							<section class="entry-content">
								<div class="entry-content__top profile-top">
									<h1 class="profile-top__title">Upload New Entry</h1>
									<p class="profile-top__text">
										New entries allow you to share your knowledge of Kytherian history or discover more about your Kytherian heritage. Select a category and up to two subcategories to classify your entry. For best practices on links and image uploads, view our <a href="#" class="paragraf-link">Entry Best Practices</a> in the Help section.
									</p>
								</div>
								{!! Form::open(array('action'=>'EntryController@next', 'method'=>'POST', 'class'=>'entry-content__form upload-form','id'=>'upload-new-entry-form' ,'autocomplete'=>'off')) !!}
{{--								<form action="#" class="entry-content__form upload-form" id="upload-new-entry-form">--}}
									<div class="upload-form__row row-flex">
										<div class="upload-form__select select-form">

											<select class="form-control" name="entry[cats][0]" id="entry[cats][0]">
												{!! xmenu::categories($categories, $selectedCatId ? $selectedCatId : Input::old('entry.cats.0'), 0) !!}
											</select>
										</div>
										<div class="upload-form__select select-form">
											<select class="form-control" name="entry[cats][1]" id="entry[cats][1]">
												{!! xmenu::categories($categories, Input::old('entry.cats.1'), 0) !!}
											</select>
										</div>
										<div class="upload-form__select select-form">
											<select class="form-control" name="entry[cats][2]" id="entry[cats][2]">
												{!! xmenu::categories($categories, Input::old('entry.cats.2'), 0) !!}
											</select>
										</div>
									</div>
									<label xstyle="font-weight: 100" class="control-label" for="entry[v]">Related village</label>
									<select class="form-control" name="entry[v]" id="entry[v]">
										<option value="0">-</option>
										@foreach($villages as $village)
											<option value="{!! $village->id !!}"  {!! Input::old('entry.v') == $village->id ? 'selected="selected"' : '' !!}>{!! $village->village_name !!}</option>
										@endforeach
									</select>
									<div class="upload-form__row drag-drop" data-files="image">
										<div class="upload-form__instruction">
											<span>Upload Photos</span> 3 photos max, 800 px minimum width, jpg/png/tiff accepted
										</div>
										<div class="drag-drop__field">
											<input type="file" class="drop-files-input" id="image-file-input" accept="image/png, image/jpg, image/jpeg, image/tiff, image/webp" multiple>
											<label for="image-file-input" class="drag-drop__box" id="image-dropbox">
												<p class="drag-drop__placeholder">Drag and Drop Photos or Click Box to Upload From Computer</p>
											</label>
										</div>
										<ul class="drag-drop__downloaded list-files"></ul>
									</div>
									<div class="upload-form__row entry-info">
										<div class="upload-form__label">Entry Title</div>
										<div class="entry-info__fields">
											<div class="entry-info__input">
												<input type="text" placeholder="Enter Title (50 Character Max)" name="entry[title]" value="value="{{ Input::old('entry.title') }}"">
											</div>
											<div class="entry-info__input">
												<input type="text" placeholder="Add Family Name (Optional)">
											</div>
											<div class="entry-info__input">
												<input type="text" placeholder="Add Village Name (Optional)">
											</div>
										</div>
									</div>
									<div class="upload-form__row entry-text">
										<div class="upload-form__label">Entry Text/Caption</div>
										<div class="entry-text__field">
											<textarea name="entry[content]" id="caption" placeholder="Enter Text…" ></textarea>
											{!! $errors->first('content', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
									<div class="upload-form__row drag-drop" data-files="media">
										<div class="upload-form__instruction">
											<span>Upload Files</span> For Video & Audio Files, 10mb max
										</div>
										<div class="drag-drop__field">
											<input type="file" class="drop-files-input" name="entry[images]" id="entry[images]" data-size-Mb="10" accept="video/mp4, audio/mpeg" multiple>
											<label for="media-file-input" class="drag-drop__box" id="image-dropbox">
												<p class="drag-drop__placeholder">Drag and Drop or Click Box to Upload From Computer</p>
											</label>
{{--											<input type="file" name="entry[images]" id="entry[images]" value="" />--}}
										</div>
										<ul class="drag-drop__downloaded list-files"></ul>
									</div>
									<div class="upload-form__footer form-buttons">
										<button class="form-buttons__submit form-btn" id="next" type="submit">{{ trans('locale.button.next') }}</button>
										<button class="form-buttons__reset form-btn" type="reset">Clear Entry</button>
									</div>
								{!! Form::close() !!}
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
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