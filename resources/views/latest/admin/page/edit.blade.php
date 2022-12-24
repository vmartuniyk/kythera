@extends('admin.layout.default')

@section('style')
.folders ul.navigation.l1 li a.inactive {color:#555}
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="folders">
                <h1>{!!trans('admin.page.menus')!!}</h1>
                <hr class="thin" />
                <a href="{!! URL::route('admin.page.create') !!}" title="{{ trans('admin.page.create') }}"><i class="glyphicon glyphicon-pencil create"></i></a>
                {!! $folders !!}
            </div>
            <hr>
            @if (isset($page))
            {{-- var_dump($page) --}}
            @endif
        </div>
        
        <div class="col-lg-8">
                
            <h1>{{ $title }}</h1>
            <hr class="thin" />
            
            <!-- Tabs -->
            <ul class="nav nav-tabs">
            	<li class="active"><a href="#tab-general" data-toggle="tab">{{ trans('admin.page.general') }}</a></li>
            	<li><a href="#tab-meta-data" data-toggle="tab">{{ trans('admin.page.seo') }}</a></li>
            </ul>
        	<!-- ./ tabs -->
        
        	<form class="form-horizontal" method="post" action="{!! isset($page) ? URL::route('admin.page.update', $page->id) : URL::route('admin.page.store') !!}" autocomplete="off">
        		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
        
        		<div class="tab-content">
        			<!-- General tab -->
        			<div class="tab-pane active" id="tab-general">
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
        						<textarea class="form-control full-width wysihtml5" name="content" value="content" rows="10">{{ Input::old('content', isset($page) ? $page->content : null) }}</textarea>
        						{!! $errors->first('content', '<span class="help-block">:message</span>') !!}
        					</div>
        				</div>
        			</div>
        			<!-- ./ general tab -->
        
        			<!-- Meta Data tab -->
        			<div class="tab-pane" id="tab-meta-data">
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
        		</div>
        
        		<div class="form-group">
        			<div class="col-md-12">
        				<a class="btn btn-cancel btn-default" href="{!! URL::route('admin.page.index') !!}">{{ trans('locale.button.cancel') }}</a>
        				<button type="submit" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
        			</div>
        		</div>
        	</form>
                
        
        </div><!-- col-lg-8 -->
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
