@extends('site.layout.default')

@section('title')
    {{ $entry->title }}
@stop

@section('style')
    .head h2 {color: #00adf0;font: bold 30px/30px arial;}
	ul.names {list-style:none;padding-left:0}
	ul.names {margin-bottom:40px;}
@stop


@section('content')
<div class="container">
    <div class="head">
        @include('site.document.text.blocks.index.head')
    </div>

    <div class="content">
        <!-- content -->

        <div class="col-md-8 col2">
            @include('site.page.people.names.blocks.view.content')
        </div>



        <!-- /content -->
    </div>
</div>
@stop