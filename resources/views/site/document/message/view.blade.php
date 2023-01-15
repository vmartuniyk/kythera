@extends('site.layout.default-new')

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
.personal {color:red}
.msgdoc p.author {
    color: #cccccc;
    font: 12px/12px arial;
    margin: 0;
    margin-top:10px;
}
.msgdoc p.author a {
    text-decoration-color: #000;
    text-decoration-style: solid;
}
.msgdoc p span {
    color: #000;
    font: bold 14px/14px arial;
}
@stop


@section('content')
    <div class="container">

        <div class="head">
            @include('site.document.text.blocks.index.head')
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                {{-- xmenu::entry_edit($item->user_id, $item) --}}

                <!-- txtdoc -->
                @include('site.document.message.blocks.view.content')
                <!-- /txtdoc -->
                <!-- comment -->
{{--                @include('site.document.message.blocks.view.comment')--}}
                <!-- /comment -->
            </div>


            <div class="col-md-4 sidebar">
                <!-- sidebar -->
                @if (1||!Config::get('app.debug'))
					@include('site.document.text.blocks.sidebar')
				@endif
                <!-- /sidebar -->
            </div>

            <!-- /content -->
        </div>

    </div>
@stop