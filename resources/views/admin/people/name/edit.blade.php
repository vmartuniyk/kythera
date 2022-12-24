@extends('admin.layout.default')

@section('style')
.form-error{display: block;margin-bottom: 10px;margin-top: 5px;color:red}
ul.alpha li {float:left;}
ul.alpha li a {padding-right:16px;}
ul.items {list-style:none;padding-left:0}
a.create {margin-top:7px;}
@stop


@section('content')
<div class="container">
    <h1>{!! trans('admin.names') !!}</h1>
    <hr class="thin" />

    <ul class="pagination alpha">
    @foreach($names as $letter => $items)
        <li><a href="{!!route('admin.name.index')!!}?letter={!!$letter!!}">{!!$letter!!}</a></li>
    @endforeach
    </ul>
</div>

<div class="container">
	<div class="row">
        <div class="col-md-4">
        	<h2>Names</h2>
        	<hr class="thin" />
        	<a class="pull-right" href="{!! URL::route('admin.name.create') !!}" title="{{ trans('admin.name.create') }}"><i class="glyphicon glyphicon-pencil create"></i></a>

		    @foreach($names as $letter => $items)
			    <ul class="items">
			        @foreach($items as $item)
			            <li>
			            	<a href="{!!action('Admin\AdminPeopleNameController@edit',$item->id)!!}">{!!$item->name!!}</a>
			            </li>
			        @endforeach
			    </ul>
		    @endforeach
        </div>

        <div class="col-md-8">
            <h2>{!!isset($name) ? trans('admin.name.edit') :  trans('admin.name.create')!!}</h2>
            <hr class="thin" />

            <!-- Tabs -->
            <ul class="nav nav-tabs" role="tablist">
            	<li role="presentation" class="active"><a id="tab-general" role="tab" href="#tab-general-content" data-toggle="tab">{{ trans('admin.general') }}</a></li>
            	<li role="presentation"><a id="tab-compounds" role="tab" href="#tab-compounds-content" data-toggle="tab">{{ trans('admin.village.compounds') }}</a></li>
            </ul>
        	<!-- ./ tabs -->


            {!! isset($name) ? Form::open(array('action'=>array('Admin\AdminPeopleNameController@update', $name->id), 'method'=>'PUT', 'id'=>'name', 'class'=>'form-horizontal', 'autocomplete'=>'off'))
            				   : Form::open(array('action'=>array('Admin\AdminPeopleNameController@store'), 'method'=>'POST', 'id'=>'name', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}

        		<div class="tab-content">
        			<!-- General tab -->
        			<div role="tabpanel" class="tab-pane fade in active" id="tab-general-content">
        				<div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="name">{{ trans('admin.name') }}</label>
        						<input class="form-control" type="text" name="name" id="name" value="{{ isset($name) ? $name->name : Input::old('name', null) }}" />
        						{!! $errors->first('name', '<span class="form-error">:message</span>') !!}
        					</div>
        				</div>

	                    <div class="form-group">
	                    	<div class="col-md-6">
		                        <div class="checkbox">
								    <label for="visible">
								    	<input type="hidden" name="visible" value="0"/>
								        <input type="checkbox" name="visible" value="1" id="visible" {!! isset($name) ? (($name->visible) ? 'checked="checked"' : '') : 'checked="checked"'!!}/> Visible
								    </label>
								</div>
								{!! $errors->first('visible', '<span class="form-error">:message</span>') !!}
							</div>
						</div>
        			</div>
        			<!-- ./ general tab -->

        			<!-- Compounds Data tab -->
        			<div role="tabpanel" class="tab-pane fade" id="tab-compounds-content">
	        			@if (isset($name))
	        				<div class="form-group">
	        					<div class="col-md-12">

        							<label class="control-label" for="compound">Add compound</label>
        							<select class="form-control" name="compound" id="compound">
        								<option value="">Select compound...</option>
        							@foreach($allCompounds as $i=>$compound)
        								<option value="{!!$compound->id!!}">{!!$compound->name!!}</option>
        							@endforeach
        							</select>
        							<br/>

	        						@if (!count($compounds))
	        							<label class="control-label" for="compounds">Name has no compounds.</label>
	        						@else
	        							<label class="control-label" for="compounds">Compounds</label>
				        				@foreach($compounds as $i=>$compound)
				        					<p>{!!++$i!!}. {{ $compound->name }}
				        					<a href="#" title="{!!trans('locale.item.delete')!!}"
				        						data-toggle="modal"
				        						data-target="#confirm-delete"
				        						data-id={!!$compound->id!!}
				        						data-title="{!!trans('locale.delete.confirm')!!}"
				        						data-message="{!!trans('locale.delete.compound.confirm.question', array('value'=>$compound->name))!!}"
				        						data-action="{!!URL::route('admin.name.compound.destroy', array($name->id, $compound->id))!!}"><i class="glyphicon glyphicon-trash"></i></a>
				        						</p>
				        				@endforeach
        							@endif
	        					</div>
	        				</div>
	        			@else
	        				<div class="form-group">
	        					<div class="col-md-12">
        							<label class="control-label" for="compound">Add compound</label>
        							<select class="form-control" name="compound" id="compound">
        								<option value="">Select compound...</option>
	        							@foreach($allCompounds as $i=>$compound)
	        								<option value="{!!$compound->id!!}">{!!$compound->name!!}</option>
	        							@endforeach
        							</select>
        							<br/>
		        					<label class="control-label" for="compound1">Name has no compounds.</label>
	        					</div>
	        				</div>
	        			@endif
        			</div>
        			<!-- /Compounds Data tab -->

        		</div>

        		<hr class="thin" />
        		<div class="form-group">
        			<div class="col-md-12">
        				<a class="btn btn-cancel btn-default" href="{!! URL::route('admin.name.index') !!}">{{ trans('locale.button.cancel') }}</a>

        				@if (isset($name))
       					<a class="btn btn-danger danger" href="#" title="{!!trans('locale.item.delete')!!}"
       						data-toggle="modal"
       						data-target="#confirm-delete"
       						data-id={!!$name->id!!}
       						data-title="{!!trans('locale.delete.confirm')!!}"
       						data-message="{!!trans('locale.delete.confirm.question', array('value'=>$name->name))!!}"
       						data-action="{!!URL::route('admin.name.destroy', $name->id)!!}">{{ trans('locale.button.delete') }}</a>
        				@endif

        				<button type="submit" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
        			</div>
        		</div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

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
<script>
	jQuery(function(){
		//show specific tab
		if (hash = window.location.hash.substr(1))
			jQuery('#'+hash).tab('show');
    });
</script>
@stop
