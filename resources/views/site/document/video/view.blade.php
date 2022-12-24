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
.videodoc-view div {margin-left:0}
.videodoc-view.text .view.video {margin-left:10px}
.jp-video-270p {
    width: 482px;
}
.jp-controls-holder {
    width: 100%;
    top:0;
}
.jp-type-single {position: relative}
.jp-video .jp-toggles {
    margin: 10px 0 0;
    width:auto;
}
.jp-video .jp-volume-controls {
    left: 92px;
    top: 11px;    
}
.jp-video .jp-type-single .jp-controls {
    margin-left: 100px;
}
.jp-video .jp-current-time {
    margin-left: 10px;
    margin-top:10px;
}
.jp-audio, .jp-audio-stream, .jp-video {
    border: 1px solid #dfdfdf;
}
.jp-video .jp-progress {
    height: 4px;
}
.jp-video-play-icon {
    background: transparent url("/assets/img/play-button.png") no-repeat scroll 0 0;
    margin-left: -26px;
    margin-top: -20px;
    top: 50%;
    left: 50%;
    
    height: 42px;
    outline: 0 none;
    width: 42px;
}
.jp-video-play-icon:focus {
    background: transparent url("/assets/img/play-button.png") no-repeat scroll 0 -100px;
}
.jp-video .jp-interface {
    border-top: none;
}
.jp-current-time, .jp-duration {
    width:auto;
}
.jp-duration {
    float: left;
    text-align: left;
    margin-top:10px;
}
.jp-time-seperator {
    float: left;
    display:inline;
    font-size: 0.64em;
    font-style: oblique;
    margin-top:10px;
}
.jp-jplayer {
    cursor: pointer;
}
.jp-jplayer object {
    cursor: pointer;
}
.jp-video .overlay {
    background: transparent url("/assets/img/overlay70.png") repeat scroll 100% 100%;
    left: 0;
    padding: 19px 16px;
    position: absolute;
    top: 0;
    width: 481px; height: 270px;
    cursor: pointer;
}
.jp-video .overlay h2 {
    color: white;
    font: bold 23px/23px arial;
}

.jp-video .overlay .play {
    background: transparent url("/assets/img/play-button.png") no-repeat scroll 0 0;
    display: block;
    left: 209px;
    margin: auto 0;
    outline: 0 none;
    position: relative;
    top: 64px;
    width: 62px;
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
                @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif
                
                <!-- txtdoc -->
                @include('site.document.video.blocks.view.content')
                <!-- /txtdoc -->
                <!-- comment -->
                @include('site.document.text.blocks.view.comment')
                <!-- /comment -->
            </div>


            <div class="col-md-4 sidebar">
                <!-- sidebar -->
                {{--@include('site.document.text.blocks.sidebar')--}}
                <!-- /sidebar -->
            </div>

            <!-- /content -->
        </div>

    </div>
@stop