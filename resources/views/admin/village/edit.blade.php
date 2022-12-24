@extends('admin.layout.default')

@section('style')
.form-error{display: block;margin-bottom: 10px;margin-top: 5px;color:red}
ul.alpha li {float:left;}
ul.alpha li a {padding-right:16px;}
ul.items {list-style:none;padding-left:0}
a.create {margin-top:7px;}
.gllpMap {width:100%; height:250px;}
@stop


@section('content')
<div class="container">
    <h1>{!! trans('admin.villages') !!}</h1>
    <hr class="thin" />

    <ul class="pagination alpha">
    @foreach($villages as $letter => $items)
        <li><a href="{!! URL::route('admin.village.index') !!}?letter={!! $letter !!}">{!!$letter!!}</a></li>
    @endforeach
    </ul>
</div>

<div class="container">
	<div class="row">
        <div class="col-md-4">
        	<h2>Villages</h2>
        	<hr class="thin" />
        	<a class="pull-right" href="{!! URL::route('admin.village.create') !!}" title="{{ trans('admin.village.create') }}"><i class="glyphicon glyphicon-pencil create"></i></a>

		    @foreach($villages as $letter => $items)
			    <ul class="items">
			        @foreach($items as $item)
			            <li>
			            	<a href="{!!action('Admin\AdminVillageController@edit',$item->id)!!}">{!!$item->village_name!!}</a>
			            </li>
			        @endforeach
			    </ul>
		    @endforeach
        </div>

        <div class="col-md-8">
            <h2>{!!isset($village) ? trans('admin.village.edit') :  trans('admin.village.create')!!}</h2>
            <hr class="thin" />

            <!-- Tabs -->
            <ul class="nav nav-tabs" role="tablist">
            	<li role="presentation" class="active"><a id="tab-general" role="tab" href="#tab-general-content" data-toggle="tab">{{ trans('admin.general') }}</a></li>
            	<li role="presentation"><a id="tab-compounds" role="tab" href="#tab-compounds-content" data-toggle="tab">{{ trans('admin.village.compounds') }}</a></li>
            	<li role="presentation"><a id="tab-longitude" role="tab" href="#tab-longitude-content" data-toggle="tab">{{ trans('admin.village.geolocation') }}</a></li>
            </ul>
        	<!-- ./ tabs -->


            {!! isset($village) ? Form::open(array('action'=>array('Admin\AdminVillageController@update', $village->id), 'method'=>'PUT', 'id'=>'village', 'class'=>'form-horizontal', 'autocomplete'=>'off'))
            				   : Form::open(array('action'=>array('Admin\AdminVillageController@store'), 'method'=>'POST', 'id'=>'village', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}

        		<div class="tab-content">
        			<!-- General tab -->
        			<div role="tabpanel" class="tab-pane fade in active" id="tab-general-content">
        				<div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="village_name">{{ trans('admin.name') }}</label>
        						<input class="form-control" type="text" name="village_name" id="village_name" value="{{ isset($village) ? $village->village_name : Input::old('village_name', null) }}" />
        						{!! $errors->first('village_name', '<span class="form-error">:message</span>') !!}
        					</div>
        				</div>

	                    <div class="form-group">
	                    	<div class="col-md-6">
		                        <div class="checkbox">
								    <label for="lost">
								    	<input type="hidden" name="lost" value="0"/>
								        <input type="checkbox" name="lost" value="1" id="lost"  {!! isset($village) && $village->lost ? 'checked="checked"' : ''!!}/> Lost
								    </label>
								</div>
								{!! $errors->first('lost', '<span class="form-error">:message</span>') !!}
							</div>
	                    	<div class="col-md-6">
		                        <div class="checkbox">
								    <label for="visible">
								    	<input type="hidden" name="visible" value="0"/>
								        <input type="checkbox" name="visible" value="1" id="visible" {!! isset($village) ? (($village->visible) ? 'checked="checked"' : '') : 'checked="checked"'!!}/> Visible
								    </label>
								</div>
								{!! $errors->first('visible', '<span class="form-error">:message</span>') !!}
							</div>
						</div>
        			</div>
        			<!-- ./ general tab -->

        			<!-- Compounds Data tab -->
        			<div role="tabpanel" class="tab-pane fade" id="tab-compounds-content">
	        			@if (isset($village))
	        				<div class="form-group">
	        					<div class="col-md-12">

        							<label class="control-label" for="compound">Add compound</label>
        							<select class="form-control" name="compound" id="compound">
        								<option value="">Select compound...</option>
        							@foreach($allCompounds as $i=>$compound)
        								<option value="{!!$compound->id!!}">{!!$compound->village_name!!}</option>
        							@endforeach
        							</select>
        							<br/>

	        						@if (!count($compounds))
	        							<label class="control-label" for="compounds">Village has no compounds.</label>
	        						@else
	        							<label class="control-label" for="compounds">Compounds</label>
				        				@foreach($compounds as $i=>$compound)
				        					<p>{!!++$i!!}. {{ $compound->village_name }}
				        					<a href="#" title="{!!trans('locale.item.delete')!!}"
				        						data-toggle="modal"
				        						data-target="#confirm-delete"
				        						data-id={!!$compound->id!!}
				        						data-title="{!!trans('locale.delete.confirm')!!}"
				        						data-message="{!!trans('locale.delete.compound.confirm.question', array('value'=>$compound->village_name))!!}"
				        						data-action="{!!URL::route('admin.village.compound.destroy', array($village->id, $compound->id))!!}"><i class="glyphicon glyphicon-trash"></i></a>
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
	        								<option value="{!!$compound->id!!}">{!!$compound->village_name!!}</option>
	        							@endforeach
        							</select>
        							<br/>
		        					<label class="control-label" for="compound1">Village has no compounds.</label>
	        					</div>
	        				</div>
	        			@endif
        			</div>
        			<!-- /Compounds Data tab -->


        			<!-- longitude tab -->
        			<div role="tabpanel" class="tab-pane fade in" id="tab-longitude-content">

	                    <div class="form-group">

	                    	<div class="col-md-4">
							    <label class="control-label" for="gllpLatitude">Latitude</label>
						    	<input class="form-control" id="latitude" type="text" name="latitude" class="gllpLatitude" value="{!!(isset($village) && $village->latitude) ? $village->latitude : ''!!}"/>
								{!! $errors->first('latitude', '<span class="form-error">:message</span>') !!}
							</div>

	                    	<div class="col-md-4">
							    <label class="control-label" for="gllpLongitude">Longitude</label>
						    	<input class="form-control" id="longitude" type="text" name="longitude" class="gllpLongitude" value="{!!(isset($village) && $village->longitude) ? $village->longitude : ''!!}"/>
								{!! $errors->first('longitude', '<span class="form-error">:message</span>') !!}
							</div>

							<div class="col-md-4">
								<label class="control-label">Find GEO location</label><br/>
		       					<a class="btn btn-default" href="#" title="Find GEO location"
		       						data-toggle="modal"
		       						data-target="#find-longitude">{{ trans('locale.button.search') }}</a>
							</div>
						</div>
        			</div>
        			<!-- /longitude tab -->

        		</div>

        		<hr class="thin" />
        		<div class="form-group">
        			<div class="col-md-12">
        				<a class="btn btn-cancel btn-default" href="{!! URL::route('admin.village.index') !!}">{{ trans('locale.button.cancel') }}</a>

        				@if (isset($village))
       					<a class="btn btn-danger danger" href="#" title="{!!trans('locale.item.delete')!!}"
       						data-toggle="modal"
       						data-target="#confirm-delete"
       						data-id={!!$village->id!!}
       						data-title="{!!trans('locale.delete.confirm')!!}"
       						data-message="{!!trans('locale.delete.confirm.question', array('value'=>$village->village_name))!!}"
       						data-action="{!!URL::route('admin.village.destroy', $village->id)!!}">{{ trans('locale.button.delete') }}</a>
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

    <div class="modal fade" id="find-longitude" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
	            <form id="form-longitude" class="form-inline">
	                <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                    <h4 class="modal-title">Find latitude/longitude</h4>
	                </div>
	                <div class="modal-body">
						<fieldset id="gllpLatlonPicker" class="gllpLatlonPicker">
							<label class="control-label" for="search">Search for location or move the balloon with your mouse to find the coordinates.</label>
							<input id="gllpSearchField" type="text" class="form-control gllpSearchField" placeholder="Location or city...">
							<input id="gllpSearchButton" type="button" class="btn btn-default gllpSearchButton" value="Search">
							<br/><br/>
							<div class="gllpMap">Google Maps</div>
							<br/>

							<div class="form-group">
								<label class="control-label" for="modalLat">Latitude</label><br/>
								<input class="form-control gllpLatitude" id="modalLat" type="text" value="36.254571646500175"/>
							</div>

							<div class="form-group">
								<label class="control-label" for="modalLon">Longitude</label><br/>
								<input class="form-control gllpLongitude" id="modalLon" type="text" value="22.97398638984373"/>
							</div>

							<br/>
							<input type="hidden" class="gllpZoom" value="10"/>
						</fieldset>
	                </div>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-default" data-dismiss="modal" data-result="cancel">{{ trans('locale.button.cancel') }}</button>
	                    <button type="submit" class="btn btn-black" data-result="save">{{ trans('locale.button.save') }}</a>
	                </div>
	            </form>
            </div>
        </div>
    </div>
@stop

@section('javascript')
<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script src="/assets/vendors/wimagguc/js/jquery-gmaps-latlon-picker.js"></script>

<script>
	jQuery(function(){
		//show specific tab
		if (hash = window.location.hash.substr(1))
			jQuery('#'+hash).tab('show');
    });

	jQuery(function(){
		//onShown modal
        jQuery("#find-longitude").on('shown.bs.modal', function(){
			jQuery('#gllpSearchField').val(jQuery('#village_name').val() ? jQuery('#village_name').val() + ', Kythera, Greece' : '');

         	jQuery('#modalLat').val(jQuery('#latitude').val() ? jQuery('#latitude').val() : 36.254571646500175);
        	jQuery('#modalLon').val(jQuery('#longitude').val() ? jQuery('#longitude').val() : 22.97398638984373);

        	jQuery(document).gMapsLatLonPicker().init(jQuery("#gllpLatlonPicker"));

        	if (jQuery('#village_name').val() && !jQuery('#latitude').val() && !jQuery('#longitue').val())
        		jQuery('#gllpSearchButton').click();
    	});

    	//onHide modal
    	jQuery("#find-longitude").on('hide.bs.modal', function(event) {
    	});

    	//OnEnter for search field
	    jQuery('#gllpSearchField').keypress(function(event){
	        if (event.keyCode == 13) {
	        	jQuery('#gllpSearchButton').click()
	            event.preventDefault();
	        }
	    });

    	//onSave
    	jQuery("#form-longitude").submit(function(event){
			jQuery('#latitude').val(jQuery('#modalLat').val())
			jQuery('#longitude').val(jQuery('#modalLon').val())
			jQuery('#find-longitude').modal('hide');
			event.preventDefault();
		});
	});
</script>
@stop
