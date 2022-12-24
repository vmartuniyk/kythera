@extends("admin.layout.default")
@section("content")
    <div class="import">
        <h1>{{ $title }} ({!! count($items) !!})</h1>
        <table class="table">
        @foreach ($items as $entry_id=>$item)
            <tr>
            <td>{!!++$i!!}</td>
            <td><a href="?entry_id={!!$entry_id!!}">{{ $item[1]->language01 }} ({!!$entry_id!!})</a></td>
            <td>{{ $item[2]->language01 }}</td>
            </tr>
        @endforeach
        </table>
    </div>
@stop