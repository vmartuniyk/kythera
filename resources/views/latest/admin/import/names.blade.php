@extends("admin.layout.default")

@section('style')
ul.items {list-style:none;padding-left:0;padding-bottom:30px}
ul.alpha li {float:left;}
ul.alpha li a {padding-right:16px;}
.names {padding-right:30px !important;padding-left:0 !important}
@stop

@section("content")
    <div class="import">
        <h1>{{ $title }} ({!! count($names) !!})</h1>
            <ul class="items alpha">
            @foreach($names as $letter => $items)
                <li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
            @endforeach
            <br class="clear"/>
            </ul>
            
            <div class="col-md-4 names">
            @foreach($names as $letter => $items)
                @if (($letter == 'I') || ($letter == 'P'))
                    </div>
                    <div class="col-md-4 names">
                @endif
                
                <h3><a id="{!!$letter!!}">{!!$letter!!}</a></h3>
                <hr class="gray"/>
                <ul class="items">
                    @foreach($items as $item)
                        <li>{!!$item->name!!}</li>
                    @endforeach
                </ul>
            @endforeach
            </div>
    </div>
@stop

