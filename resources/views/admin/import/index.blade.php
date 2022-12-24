@extends("admin.layout.default")
@section("content")
    @include('admin.import.form')

    <div class="import">
        <h1>{{ $title }} ({!! count($items) !!})</h1>
        <table class="table">
        @foreach ($items as $entry_id=>$item)
        
            <!-- disabled doc -->
            @if($item[1]->documentEnabled<1)
            {{--*/ $s='color:red' /*--}}
            @else
            {{--*/ $s='color:inherit' /*--}}
            @endif
                        
        
        
            <tr>
            <td style="{!!$s!!}">{!!++$i!!}</td>
            <td style="{!!$s!!}"><a href="?entry_id={!!$entry_id!!}&c={!!Input::get('c')!!}">{{ $item[1]->language01 }} ({!!$entry_id!!})</a></td>
            <td style="{!!$s!!}">{{ $item[2]->language01 }}</td>
            </tr>
        @endforeach
        </table>
    </div>
@stop