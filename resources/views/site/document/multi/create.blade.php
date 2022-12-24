@extends('site.layout.default')

@section('title')
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
.entry p.change {font-weight:700;}
.entry a {color: #00adf0;}
#uploader_container .ui-state-error p {color:red !important}
@stop

@section('content')
<div class="container">
    <div class="head force-left">
      <h1 class="pull-left">Add multiple entries</h1>
      <div class="crumb pull-right">Home > <span>Add multiple entries</span></div>
        <br class="clear"/>
    </div>
    <hr class="thin"/>
    <div class="content entry multi">
       			{{--
                <div class="form-error">
                    {!! HTML::ul($errors->all()) !!}
                </div>
                {!!print_r($errors,1)!!}
                --}}

                <p class="change">Change to <a href="{!!action('EntryController@create')!!}">single entry form</a>.</p>
                <h2>Multiple entries:</h2>

                <div class="form-error">
                    @if(Session::has('global'))
                    {!! Session::get('global') !!}
                    @endif
                </div>

                {!! Form::open(array('action'=>'EntriesController@next', 'method'=>'POST', 'id'=>'entry', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
                    <div class="form-group">
                        <label for="uploader">Seperate files will be visible in MULTIPLE entries on the site. For a<span class="blue"> single</span> entry <a  class="blue" href="{!!action('EntryController@create')!!}">click here</a>.</label>
						<div id="uploader">
						    <input type="file" name="entry[images]" id="entry[images]" value="" />
						</div>
                    </div>

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
                        <label class="control-label" for="entry[o]">Terms & conditions</label>
                        <div class="checkbox">
						    <label for="entry[t]">
						        <input type="checkbox" name="entry[t]" id="entry[t]" {{ Input::old('entry.t') ? 'checked':'' }}/> I have read the <a href="#">terms of use</a> and accept them.
						    </label>
						</div>
						{!! $errors->first('t', '<span class="help-block">:message</span>') !!}
	                </div>
	                -->

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right" disabled="disabled">{{ trans('locale.button.next') }}</button>
                    </div>
                </form>

    </div>
</div><!-- container -->
@stop


@section('javascript')
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="/xhtml/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.custom.css" type="text/css" />
<style>.ui-state-default .ui-icon { background-image: url(/xhtml/img/ui-icons_888888_256x240.custom.png) /*{iconsDefault}*/; }</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/xhtml/plupload/js/moxie.custom.js"></script>
<script type="text/javascript" src="/xhtml/plupload/js/plupload.dev.custom.js"></script>
<script type="text/javascript" src="/xhtml/plupload/js/jquery.ui.plupload/jquery.ui.plupload.custom.js"></script>
<script>
	jQuery(function() {
		var pluploadCustom = {
			max_file_count: 10,
			max_queue_count: 10,
			multiple_queues: true,
			resize: false,
			filters : {
				max_file_size : '20mb',
				mime_types: [
					{title : "Image files", extensions : "jpg,gif,png"}
				]
			}
		}
    	jQuery("#uploader").plupload(jQuery.extend({}, pluploadDefault, pluploadCustom));
		setTimeout(function() {
			jQuery('#uploader').plupload('addFiles', {!! json_encode($files) !!});
		}, 1000);
		jQuery("#uploader").on('complete', function(){
			$('#next').attr('disabled', false);
		});
		/*
		jQuery("#uploader").on('start', function(){
			$('#next').attr('disabled', 'disabled');
		});
		*/
    });
</script>
@stop