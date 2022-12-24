@extends("admin.layout.default")
@section("content")
    <h1>People > Names({!! array_sum($letters) !!})</h1>

    <br />
    <ul class="pagination">
    @foreach($letters as $letter => $n)
        <li><a href="?l={!!$letter!!}" class="btn btn-default btn-xs {!! Session::get('names.query') == $letter ? 'active' : '' !!}">{!!$letter!!}</a></li>
    @endforeach
    </ul>
    <span class="pull-right">{!! $items->render() !!}</span>
    
    <table class="table table-striped table-hover table-condensed">
        <tr>
            <th>Nº</th>
            <th>Firstname</th>
            <th>Middlename</th>
            <th colspan="2">Lastname</th>
            <th><a href="{!! URL::route('admin.people.names.create') !!}" title="{{ trans('admin.people.names.create') }}"><i class="glyphicon glyphicon-pencil"></i></a></th>
        </tr>
        @foreach($items as $i=>$item)
        <tr>
            <td>{!! ($i+1) !!}</td>
            <td>{{ $item->firstname }}</td>
            <td>{{ $item->middlename }}</td>
            <td>{{ $item->lastname }}</td>
            <td><a href="{!! URL::route('admin.people.names.edit', $item->id) !!}" title="{{ trans('admin.people.names.edit') }}"><i class="glyphicon glyphicon-edit"></i></a></td>
            @if ($item->id)
            <td><a href="#" title="{{ trans('admin.people.names.delete') }}"
                data-toggle="modal"
                data-target="#confirm-delete"
                data-id={!!$item->id!!}
                data-title="{!! trans('general.confirm') !!}"
                data-message="{{ trans('admin.people.names.delete.confirm', array('title'=>$item->lastname)) }}"
                data-action="{!! URL::route('admin.people.names.destroy', $item->id) !!}"><i class="glyphicon glyphicon-trash"></i></a>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('buttons.cancel') }}</button>
                        <button type="submit" class="btn btn-danger danger">{{ trans('buttons.delete') }}</button>
                    </div>
            		{!! Form::token() !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
    

    
@stop