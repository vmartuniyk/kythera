@extends('site.layout.default')

@section('title')
	{{ $page->title }} - {{ $item->title }}
@stop

@section('style')
/*
.head h2 {color: #00adf0;font: bold 30px/30px arial;}

.txtdoc-view > .line {margin-bottom:0}
.txtdoc-view .txtdoc {margin-bottom:22px}
.txtdoc img {display:none;} .txtdoc div {width:100%}
.txtdoc-view h1 {color: #000;font: bold 34px/34px arial;margin: 30px 0 0;}
.txtdoc-view div {margin-left: 10px;}
//.txtdoc-view p {font:18px/24px Arial; color:#555}
.txtdoc-view p img {display:block;margin:0 auto;}

.txtdoc-comment {margin-left: 10px;}
*/
@stop


@section('content')
    <div class="container">

        <div class="head">
            @include('site.document.text.blocks.index.head')
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif
                
                <!-- txtdoc -->
                @include('site.document.audio.blocks.view.content')
                <!-- /txtdoc -->
                <!-- comment -->
                @include('site.document.text.blocks.view.comment')
                <!-- /comment -->
            </div>


            <div class="col-md-4 sidebar">
                <!-- sidebar -->
                @include('site.document.text.blocks.sidebar')
                <!-- /sidebar -->
            </div>

            <!-- /content -->
        </div>

    </div>
@stop