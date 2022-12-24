@extends('admin.layout.default')

@section('style')
ul.alpha li {float:left;}
ul.alpha li a {padding-right:16px;}
ul.items {list-style:none;padding-left:0}
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
        	<a class="pull-right" href="{!! URL::route('admin.village.create') !!}" title="{{ trans('admin.village.create') }}"><i class="glyphicon glyphicon-pencil"></i></a>
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
            <h2>{!!trans('admin.village.edit.title')!!}</h2>
            <hr class="thin" />

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
