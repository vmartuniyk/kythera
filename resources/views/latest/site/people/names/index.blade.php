@extends('site.layout.default')

@section('title')
@parent
 :: {{ Lang::get('site.people.names.index') }}
@stop

@section('style')
ul.items {list-style:none;padding-left:0}
ul.alpha li {float:left;}
ul.alpha li a {padding-right:16px;}
.names {padding-right:30px;}
@stop

@section('content')
    <h1>People > Names</h1>
    <br />
    
    <!-- first col -->
    <div class="col-md-8">
    
        <ul class="items alpha">
        @foreach($names as $letter => $items)
            <li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
        @endforeach
        <br class="clear"/>
        </ul>
        
        <div class="col-md-4 names">
        @foreach($names as $letter => $items)
            @if ((in_array($letter, array('I', 'P', '?', '?'))))
                </div>
                <div class="col-md-4 names">
            @endif
            
            <h3><a id="{!!$letter!!}">{!!$letter!!}</a></h3>
            <hr class="gray"/>
            <ul class="items">
                @foreach($items as $item)
                    <li><a href="{!! action('PeopleNamesController@getShow', Str::lower($item->lastname)) !!}">{!!$item->lastname!!}</a></li>
                @endforeach
            </ul>
        @endforeach
        </div>
    
    </div><!-- first col -->
    
    <!-- second col -->
    <div class="col-md-4">
        [[RECENT POSTS]]
    </div><!-- second col -->
    
@stop