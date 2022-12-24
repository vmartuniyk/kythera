@extends("admin.layout.default")

@section('style')
@stop

@section("content")
    <h1>{!! trans('admin.document_types') !!} ({!! $items->count() !!})</h1>
    <hr class="thin" />

    <span class="pull-right">{!! $items->render() !!}</span>
    
    <table class="table table-striped table-hover table-condensed">
        <tr>
        @foreach($items[0]->getAttributes() as $k => $v)
        <th>{!! $k !!}</th>
        @endforeach
        <th></th>
        </tr>
    
        @foreach($items as $item)
        <tr>
            @foreach($item->getAttributes() as $k => $v)
            <td>{!! $v !!}</td>
            @endforeach
            
            @if ($item->id)
            <td><a href="#" title="{{ trans('admin.delete') }}"
                data-toggle="modal"
                data-target="#confirm-delete"
                data-id={!!$item->id!!}
                data-title="{!! trans('locale.delete.confirm') !!}"
                data-message="{{ trans('locale.delete.confirm.question', array('value'=>$item->string_id)) }}"
                data-action="{!! action('Admin\AdminDocumentTypesController@destroy', $item->id) !!}"><i class="glyphicon glyphicon-trash"></i></a>
            </td>
            @endif
            
        </tr>
        @endforeach
    </table>
        
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(array('method'=>'DELETE')) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">{title}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('locale.button.cancel') }}</button>
                        <button type="submit" class="btn btn-danger danger">{{ trans('locale.button.delete') }}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop