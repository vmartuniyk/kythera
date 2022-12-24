@extends('site.layout.default')

@section('title')
    Add detailsssdfsdf
@stop

@section('style')
/*
.list img {width:100%;}
.list label.control-label {margin-top:10px;}


.list .thumb {width:150px;margin-right:20px;border:1px solid #B6B6B6;float:left;text-align:center;height:150px;border-radius:4px;padding:2px}
.list .thumb.media {line-height: 138px;font-size: 21px;font-weight: bold;text-transform: lowercase;color:gray}

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

                {!! Form::open(array('action' => array('EntryController@update', $entry->id), 'method' => 'PUT', 'id' => 'entry', 'class' => 'form-horizontal')) !!}
                    <div class="list">
			        @foreach($files as $i=>$file)

				        <div>
				        	{{--
				        	<!--
			                <div class="xform-group">
		                        <label class="control-label" for="files[{!!$i!!}][t]">Title</label>
		                        <input class="form-control required" type="text" name="files[{!!$i!!}][title]" id="files[{!!$i!!}][title]" value="{{ Input::old('entry.title') }}" xrequired="required"/>
		                        {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
		                    </div>
		                    -->
		                    --}}
		                    <div class="details clearfix">


					        	@if ($file['type'] == 'image')
					        	<div class="thumb"><img src="{!!$file['uri']!!}" /></div>
					        	@elseif ($file['type'] == 'audio')
					        	<div class="thumb media audio"></div>
					        	@elseif ($file['type'] == 'video')
					        	<div class="thumb media video"></div>
					        	@else
					        	<div class="thumb unknown">?</div>
					        	@endif
					        	<input type="hidden" name="files[{!!$i!!}][kfnid]" value="{!!$file['kfnid']!!}"/>
					        	<input type="hidden" name="files[{!!$i!!}][f]" value="{!!$file['uri']!!}"/>
					        	<input type="hidden" name="files[{!!$i!!}][t]" value="{!!$file['type']!!}"/>

					        	<div class="pull-left">
					        	<h2>{!!$file['caption']!!}</h2>
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

		                    {{--
		                    <!--
						    <label class="control-label" xfor="files[c]">Choose up to three categories for your entry</label>
						    <div class="category-groups clearfix">
						        <div class="col-md-4 category-group">
								    <label style="font-weight: 100" xclass="control-label" for="files[{!!$i!!}][cats][0]">Category 1</label>
								    <select class="form-control" name="files[{!!$i!!}][cats][0]" id="files[{!!$i!!}][cats][0]">
	    							    @foreach($categories as $category)
	    							    <option value="{!! $category->controller_id !!}">{!! $category->title !!}</option>
	    							    @endforeach
								    </select>
						        </div>
						        <div class="col-md-4 category-group">
								    <label style="font-weight: 100" xclass="control-label" for="files[{!!$i!!}][cats][1]">Category 2</label>
								    <select class="form-control" name="files[{!!$i!!}][cats][1]" id="files[{!!$i!!}][cats][1]">
	                                    <option value="0">-</option>
	    							    @foreach($categories as $category)
	    							    <option value="{!! $category->controller_id !!}">{!! $category->title !!}</option>
	    							    @endforeach
	  							    </select>
						        </div>
						        <div class="col-md-4 category-group">
								    <label style="font-weight: 100" xclass="control-label" for="files[{!!$i!!}][cats][2]">Category 3</label>
								    <select class="form-control" name="files[{!!$i!!}][cats][2]" id="files[{!!$i!!}][cats][2]">
	                                    <option value="0">-</option>
	    							    @foreach($categories as $category)
	    							    <option value="{!! $category->controller_id !!}">{!! $category->title !!}</option>
	    							    @endforeach
								    </select>
						        </div>
						    </div>
						    -->
						    --}}


    						<div class="form-group">
						    <div class="category-groups clearfix">
						    	{{--
						    	<!--
						        <div class="col-md-4 category-group">
								    <label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][v]">Related village</label>
								    <select class="form-control" name="files[{!!$i!!}][v]" id="files[{!!$i!!}][v]">
								    	<option value="0">-</option>
	    							    @foreach($villages as $village)
	    							    <option value="{!! $village->id !!}">{!! $village->village_name !!}</option>
	    							    @endforeach
								    </select>
						        </div>
						        -->
						        --}}

						        <div class="col-md-4 category-group">
								    <label xstyle="font-weight: 100" class="control-label" for="files[{!!$i!!}][c]">Copyright owner</label>
								    <input type="text" class="form-control" name="files[{!!$i!!}][c]" id="files[{!!$i!!}][c]" value="{!!$entry->copyright!!}">
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
								    <input type="text" class="form-control xdate xdateITA" name="files[{!!$i!!}][d]" id="files[{!!$i!!}][d]" placeholder="dd/mm/yyyy or mm/yyyy or yyyy" value="{!!$file['taken']!!}" />
   						        </div>
						    </div>
						    </div>


				        </div>
			        @endforeach
                    </div>

                    <hr class="blue"/>

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
                    --}}

                    <div class="form-group">
                        <label class="control-label" for="p">Permission</label>
                        <div class="checkbox">
						    <label for="p">
						        <input type="checkbox" class="required" name="p" id="p" {{ Input::old('p') ? 'checked':'' }} checked="checked" xrequired="required"/> I have permission to use the provided material.
						    </label>
						</div>
						{!! $errors->first('p', '<span class="form-error">:message</span>') !!}
	                </div>

                    <div class="form-group">
                        <label class="control-label" for="t">Terms & conditions</label>
                        <div class="checkbox">
						    <label for="t">
						        <input type="checkbox" class="required" name="t" id="t" {{ Input::old('t') ? 'checked':'' }} checked="checked" xrequired="required"/> I have read the <a href="#">terms of use</a> and accept them.
						    </label>
						</div>
						{!! $errors->first('t', '<span class="form-error">:message</span>') !!}
	                </div>

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="javascript:history.back();">{{ trans('locale.button.back') }}</a>
        				
        				<button type="submit" class="btn btn-black pull-right">{{ trans('locale.button.update') }}</button>
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
    	jQuery('input.date').datepicker(jQuery.extend({}, datepickerDefault, datepickerCustom));
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
@stop