@extends('site.layout.default')

@section('title')
    Add multiple entries
@stop

@section('style')
.list img {width:150px;margin-right:20px;border:1px solid #B6B6B6;margin-top:37px;}
.list div.pull-left {padding:0;width:80%}
.list label.control-label {margin-top:10px;}
.list .thumb {border-radius:4px;padding:2px}
.action {float:right;font-size: 60px;color:red;line-height:1;opacity:0.6;margin-top:-30px}
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
                <pre>
                {!!print_r($errors,1)!!}
                {!!print_r(Input::old(),1)!!}

                @if (isset($validators))
                {!!print_r($validators,1)!!}
                @endif
                </pre>
                --}}

                <div class="form-error">
                    @if(Session::has('global'))
                    {!! Session::get('global') !!}
                    @endif
                </div>

                {!! Form::open(array('action'=>'EntriesController@store', 'method'=>'POST', 'id'=>'entry', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
                    <div class="list">
			        @foreach($images as $i=>$image)
			        	<div class="entry">
			        		<button type="button" class="action close" aria-label="Close" title="Remove entry '{{ $image['name'] }}'"><span aria-hidden="true">&times;</span></button>

				           	<input type="hidden" name="entries[{!!$i!!}][uri]" value="{!!$image['uri']!!}"/>
						   	<input type="hidden" name="entries[{!!$i!!}][type]" value="{!!$image['type']!!}"/>
						   	<input type="hidden" name="entries[{!!$i!!}][name]" value="{!!$image['name']!!}"/>
						   	<input type="hidden" name="entries[{!!$i!!}][f]" value="{!!$image['name']!!}"/>

				        	<h2>{!!$image['name']!!}</h2>
			                <div class="xform-group">
		                        <label class="control-label" for="entries[{!!$i!!}][title]">Title</label>
		                        <input class="form-control required" type="text" name="entries[{!!$i!!}][title]" id="entries[{!!$i!!}][title]" value="{{ Input::old('entries.'.$i.'.title') }}" />
		                        {!! $errors->$i->first('title', '<span class="form-error">:message</span>') !!}
		                    </div>

		                    <div class="clearfix">
					        	<img class="pull-left thumb" src="{!!$image['uri']!!}" />
					        	<input type="hidden" name="entries[{!!$i!!}][i]" value="{!!$image['uri']!!}"/>
			                    <div class="pull-left">
			                        <label class="control-label" for="entries[{!!$i!!}][content]">Caption</label>
			                        <textarea class="text form-control ckeditor" name="entries[{!!$i!!}][content]" id="entries[{!!$i!!}][content]" rows="3">{{ Input::old('entries.'.$i.'.content') }}</textarea>
			                        {!! $errors->$i->first('content', '<span class="form-error">:message</span>') !!}
			                    </div>
		                    </div>

						    <label class="control-label" xfor="entries[c]">Choose up to three categories for your entry</label>
						    <div class="category-groups clearfix">
						        <div class="col-md-4 category-group">
								    <label style="font-weight: 100" xclass="control-label" for="entries[{!!$i!!}][cats][0]">Category 1</label>
								    <select class="form-control required" name="entries[{!!$i!!}][cats][0]" id="entries[{!!$i!!}][cats][0]">
								    {{--
	    							    @foreach($categories as $category)
	    							    <option value="{!! $category->controller_id !!}" {!! Input::old('entries.'.$i.'.cats.0') == $category->controller_id ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
	    							    @endforeach
	    							    --}}
	    							    {!! xmenu::categories($categories, Input::old('entries.'.$i.'.cats.0'), 0) !!}
								    </select>
						        </div>
						        <div class="col-md-4 category-group">
								    <label style="font-weight: 100" xclass="control-label" for="entries[{!!$i!!}][cats][1]">Category 2</label>
								    <select class="form-control" name="entries[{!!$i!!}][cats][1]" id="entries[{!!$i!!}][cats][1]">
								    {{--
	                                    <option value="0">-</option>
	    							    @foreach($categories as $category)
	    							    <option value="{!! $category->controller_id !!}" {!! Input::old('entries.'.$i.'.cats.1') == $category->controller_id ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
	    							    @endforeach
	    							    --}}
	    							    {!! xmenu::categories($categories, Input::old('entries.'.$i.'.cats.1'), 0) !!}
	  							    </select>
						        </div>
						        <div class="col-md-4 category-group">
								    <label style="font-weight: 100" xclass="control-label" for="entries[{!!$i!!}][cats][2]">Category 3</label>
								    <select class="form-control" name="entries[{!!$i!!}][cats][2]" id="entries[{!!$i!!}][cats][2]">
								    {{--
	                                    <option value="0">-</option>
	    							    @foreach($categories as $category)
	    							    <option value="{!! $category->controller_id !!}" {!! Input::old('entries.'.$i.'.cats.2') == $category->controller_id ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
	    							    @endforeach
	    							    --}}
	    							    {!! xmenu::categories($categories, Input::old('entries.'.$i.'.cats.2'), 0) !!}
								    </select>
						        </div>
						    </div>


						    <div class="category-groups clearfix">
						        <div class="col-md-4 category-group">
								    <label xstyle="font-weight: 100" class="control-label" for="entries[{!!$i!!}][v]">Related village</label>
								    <select class="form-control" name="entries[{!!$i!!}][v]" id="entries[{!!$i!!}][v]">
								    	<option value="0">-</option>
	    							    @foreach($villages as $village)
	    							    <option value="{!! $village->id !!}"  {!! Input::old('entries.'.$i.'.v') == $village->id ? 'selected="selected"' : '' !!}>{!! $village->village_name !!}</option>
	    							    @endforeach
								    </select>
						        </div>
						        <div class="col-md-4 category-group">
								    <label xstyle="font-weight: 100" class="control-label" for="entries[{!!$i!!}][c]">Copyright owner</label>
								    <input type="text" class="form-control" name="entries[{!!$i!!}][c]" id="entries[{!!$i!!}][c]" value="{!! Input::old('entries.'.$i.'.c') !!}">
						        </div>
						        <div class="col-md-4 category-group">
						        	@if ($image['type'] == 'image')
								    <label xstyle="font-weight: 100" class="control-label" for="entries[{!!$i!!}][d]">Date taken</label>
						        	@elseif ($image['type'] == 'audio')
						        	<label xstyle="font-weight: 100" class="control-label" for="entries[{!!$i!!}][d]">Date recorded</label>
						        	@elseif ($image['type'] == 'video')
						        	<label xstyle="font-weight: 100" class="control-label" for="entries[{!!$i!!}][d]">Date recorded</label>
						        	@else
						        	<label xstyle="font-weight: 100" class="control-label" for="entries[{!!$i!!}][d]">Date</label>
						        	@endif
								    <input type="text" class="form-control dateITA" name="entries[{!!$i!!}][d]" id="entries[{!!$i!!}][d]" placeholder="dd/mm/yyyy" value="{!! Input::old('entries.'.$i.'.d') !!}"/>
   						        </div>
						    </div>

						    <hr class="blue"/>
				        </div>
			        @endforeach
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

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!! URL::action('EntriesController@create') !!}">{{ trans('locale.button.back') }}</a>
        				<button type="submit" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
                    </div>
                </form>

    </div>
</div><!-- container -->
@stop


@section('javascript')
	@if (Config::get('app.ckeditor'))
	<script src="//cdn.ckeditor.com/4.4.5.1/standard/ckeditor.js"></script>
	<script>
		var ckeditorCustom = {
			language: '{!!App::getLocale()!!}',
			width: '897px',
	        height: '77px'
		}
	    $('textarea').each(function(i){
	        var id = $(this).attr('id');
	        if (typeof CKEDITOR != 'undefined')
	        	CKEDITOR.replace(id, jQuery.extend({}, ckeditorDefault, ckeditorCustom));
		});
	</script>
	@endif

<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script>
	jQuery(function() {
		var datepickerCustom = {};
    	jQuery('input.dateITA').datepicker(jQuery.extend({}, datepickerDefault, datepickerCustom));
    });
</script>

<script>
    function CopyCats()
    {
    	//#entry div.list div.entry select#entries[1][cats][0]
        var value = $(".entry select:first").val();
        $(".entry select:first-child").each(function() {
            alert($(this).attr('id'));
        });
    }


	jQuery(function() {
		$(".action").on("click", function(){
			var title = $(this).attr('title');
			var item = $(this).parent();

			if (confirm(title+" ?")) {
				$(item).fadeOut(400, function(){
					$(this).remove();
				});
			}
		});
	});

	jQuery(function() {
		var ruleSetCustom = {
			//debug: true,
    		rules: {
    			p: "required",
    			t: "required",
    			"entries[0][cats][0]": {required: true, min: 1},
    			"entries[1][cats][0]": {required: true, min: 1},
    			"entries[2][cats][0]": {required: true, min: 1},
    			"entries[3][cats][0]": {required: true, min: 1},
    			"entries[4][cats][0]": {required: true, min: 1},
    			"entries[5][cats][0]": {required: true, min: 1},
    			"entries[6][cats][0]": {required: true, min: 1},
    			"entries[7][cats][0]": {required: true, min: 1},
    			"entries[8][cats][0]": {required: true, min: 1},
    			"entries[9][cats][0]": {required: true, min: 1},
    		},
    		messages: {
    			p: "You must have permission",
    			t: "You must accept terms of use",
    			"entries[0][cats][0]": "Please select at least one category.",
    			"entries[1][cats][0]": "Please select at least one category.",
    			"entries[2][cats][0]": "Please select at least one category.",
    			"entries[3][cats][0]": "Please select at least one category.",
    			"entries[4][cats][0]": "Please select at least one category.",
    			"entries[5][cats][0]": "Please select at least one category.",
    			"entries[6][cats][0]": "Please select at least one category.",
    			"entries[7][cats][0]": "Please select at least one category.",
    			"entries[8][cats][0]": "Please select at least one category.",
    			"entries[9][cats][0]": "Please select at least one category.",
    		}
		}
    	jQuery("#entry").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>

@stop