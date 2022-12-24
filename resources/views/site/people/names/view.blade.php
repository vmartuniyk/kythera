@extends('site.layout.default')

@section('title')
    {{ $entry->title }}
@stop

@section('style')
    .head h2 {color: #00adf0;font: bold 30px/30px arial;}
@stop


@section('content')
    <div class="head">
        @include('site.document.text.blocks.view.head')
    </div>

    <div class="content">
        <!-- content -->
    
        <div class="col-md-8 col2">
            @include('site.people.names.blocks.view.content')
        </div>


        <div class="col-md-4 sidebar">
            <!-- sidebar -->
            @include('site.document.text.blocks.sidebar')
            <!-- /sidebar -->
        </div>
        
        <!-- /content -->
    </div>
@stop