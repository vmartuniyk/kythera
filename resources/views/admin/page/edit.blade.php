@extends('admin.layout.default')

@section('style')
.folders .glyphicon.tree {font-size:24px}
.folders ul.navigation.l1 li a.inactive {color:#aaa}
.bg-info {background-color:green;color:white;border-radius:4px}
.folders li.selected > a {color:#00ADF0 !important;}



.btn-file {
position: relative;
overflow: hidden;
}
.btn-file input[type=file] {
position: absolute;
top: 0;
right: 0;
min-width: 100%;
min-height: 100%;
font-size: 100px;
text-align: right;
filter: alpha(opacity=0);
opacity: 0;
outline: none;
background: white;
cursor: inherit;
display: block;
}
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="folders">
                <h1>{!!trans('admin.page.menu.title')!!}</h1>
                <hr class="thin" />
                <a href="{!! URL::route('admin.page.create') !!}" title="{{ trans('admin.page.create') }}"><i class="glyphicon glyphicon-pencil create"></i></a>
                {!! $folders !!}
            </div>
            <hr>
        </div>

        <div class="col-lg-8">

            <h1>{!! trans('admin.page.edit.title') !!}</h1>
            <hr class="thin" />

            <!-- Tabs -->
            <ul class="nav nav-tabs">
            	<li class="active"><a href="#tab-general" data-toggle="tab">{{ trans('admin.page.general') }}</a></li>
                <?php
                    if (!empty($page)) {
                ?>
                @if ($page->isHomepage())
                    <li><a href="#tab-color-box" data-toggle="tab">{{ trans('admin.page.colorbox') }}</a></li>
                @endif
                <?php
                    }
                ?>
            	<li><a href="#tab-meta-data" data-toggle="tab">{{ trans('admin.page.seo') }}</a></li>
            	<li><a href="#tab-controller" data-toggle="tab">{{ trans('admin.page.controller') }}</a></li>
            </ul>
        	<!-- ./ tabs -->

        	<form class="form-horizontal" method="post" action="{!! isset($page) ? URL::route('admin.page.update', $page->id) : URL::route('admin.page.store') !!}" autocomplete="off" enctype="multipart/form-data">
        		<input type="hidden" name="_token" value="{{ csrf_token() }}" />

        		<div class="tab-content">
        			<!-- General tab -->
        			<div class="tab-pane fade in active" id="tab-general">
                	    {!! Form::label('parent', 'Position', array('class'=>'control-label') ) !!}
                	    {!! $parent_select !!}

        				<div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="title">{{ trans('admin.page.title') }}</label>
        						<input class="form-control" type="text" name="title" id="title" value="{{ Input::old('title', isset($page) ? $page->title : null) }}" />
        						{!! $errors->first('title', '<span class="help-block">:message</span>') !!}
        					</div>
        				</div>

        				<div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="title">{{ trans('admin.page.slug') }}</label>
        						<input class="form-control" type="text" name="uri" id="uri" value="{{ Input::old('uri', isset($page) ? $page->uri : null) }}" />
        						{!! $errors->first('uri', '<span class="help-block">:message</span>') !!}
        					</div>
        				</div>

        				<div class="form-group">
        					<div class="col-md-12">
                                <label class="control-label" for="content">{{ trans('admin.page.content') }}</label>
        						<textarea style="visibility:hidden" id="editor1" class="form-control full-width wysihtml5" name="content" value="content" rows="10">{{ Input::old('content', isset($page) ? $page->content : null) }}</textarea>
        						{!! $errors->first('content', '<span class="help-block">:message</span>') !!}
        					</div>
        				</div>

                        <?php
                        if (!empty($page)) {
                        ?>
                        @if ($page->isHomepage())
        				<div class="form-group">
							<div class="col-md-12">
                                <label class="control-label" for="image">{!! trans('admin.page.image') !!}</label>
                                <br/>
                                <label class="btn btn-default btn-file">
                                    Browse <input type="file" style="display: none;" name="image" id="image" value=""/>
                                </label>
                                <span id="filename"></span>
                                <br/>
                                <br/>
                                @if ($image = $page->getImageUri())
                                    <img src="{!!$image!!}" />
                                @endif
							</div>
						</div>
                        @endif
                        <?php
                        }
                        ?>
        			</div>
        			<!-- ./ general tab -->

                    <!-- color box -->
                    <?php
                    if (!empty($page)) {
                    ?>
                    @if ($page->isHomepage())
                    <div class="tab-pane fade" id="tab-color-box">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="colorbox_title">{{ trans('admin.page.title') }}</label>
                                <input class="form-control" type="text" name="colorbox_title" id="colorbox_title" value="{{ Input::old('colorbox_title', isset($page) ? $page->colorboxtitle : null) }}"/>
                                {!! $errors->first('colorbox_title', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="colorbox_url">{{ trans('admin.page.slug') }}</label>
                                <input class="form-control" type="text" name="colorbox_url" id="colorbox_url" value="{{ Input::old('colorbox_url', isset($page) ? $page->colorboxurl : null) }}" placeholder="http://"/>
                                {!! $errors->first('colorbox_url', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="colorbox">{{ trans('admin.page.content') }}</label>
                                <input class="form-control" type="text" name="colorbox" id="colorbox" value="{{ Input::old('colorbox', isset($page) ? $page->colorbox : null) }}"/>
                                {!! $errors->first('colorbox', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="colorbox_image">{!! trans('admin.page.colorbox.image') !!}</label>
                                <br/>
                                <label class="btn btn-default btn-file">
                                    Browse <input type="file" style="display: none;" id="colorbox_image" name="colorbox_image" value=""/>
                                </label>
                                <span id="colorbox_image_file"></span>
                                <br/>
                                <br/>
                                @if ($image = $page->getColorBoxImageUri())
                                    <img src="{!!$image!!}" />
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    <?php
                    }
                    ?>
                    <!-- color box -->

        			<!-- Meta Data tab -->
        			<div class="tab-pane fade" id="tab-meta-data">
        				<div class="form-group {{ $errors->has('meta-title') ? 'error' : '' }}">
        					<div class="col-md-12">
                                <label class="control-label" for="meta-title">{{ trans('admin.page.seo.title') }}</label>
        						<input class="form-control" type="text" name="meta-title" id="meta-title" value="{{ Input::old('meta-title', isset($page) ? $page->meta_title : null) }}" />
        						{{ $errors->first('meta-title', '<span class="help-block">:message</span>') }}
        					</div>
        				</div>

        				<div class="form-group {{ $errors->has('meta-description') ? 'error' : '' }}">
        					<div class="col-md-12 controls">
                                <label class="control-label" for="meta-description">{{ trans('admin.page.seo.description') }}</label>
        						<input class="form-control" type="text" name="meta-description" id="meta-description" value="{{ Input::old('meta-description', isset($page) ? $page->meta_description : null) }}" />
        						{{ $errors->first('meta-description', '<span class="help-block">:message</span>') }}
        					</div>
        				</div>

        				<div class="form-group {{ $errors->has('meta-keywords') ? 'error' : '' }}">
        					<div class="col-md-12">
                                <label class="control-label" for="meta-keywords">{{ trans('admin.page.seo.keywords') }}</label>
        						<input class="form-control" type="text" name="meta-keywords" id="meta-keywords" value="{{ Input::old('meta-keywords', isset($page) ? $page->meta_keywords : null) }}" />
        						{{ $errors->first('meta-keywords', '<span class="help-block">:message</span>') }}
        					</div>
        				</div>
        			</div>

        			<!-- Controller tab -->
        			<div class="tab-pane fade" id="tab-controller">
            			<div class="form-group {{ $errors->has('controller') ? 'error' : '' }}">
            			    <div class="col-md-12">
                    	    {!! Form::label('controller', 'Controller', array('class'=>'control-label') ) !!}
                    	    {!! Form::select('controller', $controllers, isset($page) ? $page->controller_id : '-', array('class'=>'form-control')) !!}
                    	    {{ $errors->first('controller', '<span class="help-block">:message</span>') }}
            			    </div>
        			    </div>
        			</div>

        		</div>

        		<hr class="thin" />
        		<div class="form-group">
        			<div class="col-md-12">
        				<a class="btn btn-cancel btn-default" href="{!! URL::route('admin.page.index') !!}">{{ trans('locale.button.cancel') }}</a>
        				<button type="submit" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
        			</div>
        		</div>
        	</form>


        </div><!-- col-lg-8 -->
    </div>
</div>

    <!-- confirm dialog -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">{title}</h4>
                </div>
                <div class="modal-body">
                    <p>{message}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('locale.button.cancel') }}</button>
                    <button type="submit" class="btn btn-danger danger">{{ trans('locale.button.delete') }}</a>
                </div>

        		<!-- CSRF Token -->
        		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
            </form>
            </div>
        </div>
    </div>
@stop


@section('javascript')
<script src="//cdn.ckeditor.com/4.4.5.1/standard/ckeditor.js"></script>
<script>
	var ckeditorCustom = {
		language: '{!!App::getLocale()!!}',
		xtoolbar: {!!Config::get('app.debug')?'true':'false'!!}
	}
    var editor = CKEDITOR.replace('editor1', jQuery.extend({}, ckeditorDefault, ckeditorCustom));

    @if (Config::get('app.debug'))
        //http://docs.ckeditor.com/#!/guide/dev_allowed_content_rules-section-string-format
        editor.on('instanceReady', function(evt) {
		//var editor = evt.editor;
	    console.log(editor.filter.allowedContent);
	    //editor.setData('<div class="col-md-4">1</div><hr class="clear"><a name="3">x</a><div class="col-md-6">1</div><div class="team">1</div><h1>2</h1><h2>3</h2><i>3</i><b>3b</b><p>4<br class="clear">5</p><br class="clear"><a href="http://nu.nl" title="nu.nl">9</a>10<img src="/image1.jpg" alt="image1">');
	});
	@endif

    jQuery(document).on('change', '#image', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect1', [numFiles, label]);
    });

    jQuery(document).ready( function() {
        jQuery('#image').on('fileselect1', function(event, numFiles, label) {
            console.log(numFiles);
            console.log(label);
            jQuery('#filename').text(label);
        });
    });

    jQuery(document).on('change', '#colorbox_image', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect2', [numFiles, label]);
    });

    jQuery(document).ready( function() {
        jQuery('#colorbox_image').on('fileselect2', function(event, numFiles, label) {
            console.log(numFiles);
            console.log(label);
            jQuery('#colorbox_image_file').text(label);
        });
    });




</script>
@stop
