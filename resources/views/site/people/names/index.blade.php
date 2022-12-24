@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
ul.items {list-style:none;padding-left:0}
ul.alpha li {float:left;}
ul.alpha li a {padding-right:16px;}
.names {padding-right:30px;}
@stop

@section('content')
    <div class="head">
        @include('site.document.text.blocks.index.head')
    </div>

    <div class="content">
        <!-- content -->
    
        <div class="col-md-8 col2">
            @include('site.people.names.blocks.index.content')
        </div>


        <div class="col-md-4 sidebar">
            <!-- sidebar -->
            @include('site.document.text.blocks.sidebar')
            <!-- /sidebar -->
        </div>
        
        <!-- /content -->
    </div>
    
@stop