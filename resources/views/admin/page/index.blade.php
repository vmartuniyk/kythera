@extends('admin.layout.default')

@section('style')
.folders .glyphicon.tree {font-size:24px}
.folders ul.navigation.l1 li a.inactive {color:#aaa}
/*
.folders > ul[id*="f"] {display: none;}
.folders h2 i.tree {cursor:pointer;margin-right:10px}
*/
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="folders">
                <h1>{!!trans('admin.page.menu.title')!!}</h1>
                <hr class="thin" />
                <a class="create" href="{!! URL::route('admin.page.create') !!}" title="{{ trans('admin.page.create') }}"><i class="glyphicon glyphicon-pencil create"></i></a>
                {!! $folders !!}
            </div>
        </div>

        <div class="col-lg-8">
            <h1>{!!trans('admin.page.edit.title')!!}</h1>
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