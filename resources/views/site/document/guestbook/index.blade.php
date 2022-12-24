@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
.guestbook .txtdoc {
    border-bottom: 1px solid #dfdfdf;
    padding: 20px 0;
}
.guestbook .txtdoc p {
    font: inherit;
}
.guestbook h3 {
    color: #000;
    font: 700 14px/14px arial;
    text-transform: uppercase;
}
.form-group {
	padding-left:15px
}
span.date {
    font: 12px/12px arial;
}
#recaptcha_widget_divx, #recaptcha_table {
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    xcolor: #555;
    display: block;
    xtransition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 440px;
}
#recaptcha_response_field {
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc !important;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555;
    display: block;
    font-size: 14px;
    height: 34px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 100%;
}
#recaptcha_response_field:focus {
	border-color:#66afe9;
	box-shadow: 0 1px 1px rgba(0,0,0,0.075) inset, 0 0 8px rgba(102,175,233,0.6);
	outline:0 none;
}
#recaptcha_response_field-error {
	color: red !important;
	margin-bottom:10px !important;margin-top:10px !important;
}
#recaptcha_image {
	height:59px !important;
	width:302px !important;
}
@stop

@section('content')
    <div class="container">

        <div class="head">
            @include('site.document.text.blocks.index.head')
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2 guestbook">
                <!-- txtdoc -->
                @include('site.document.guestbook.blocks.index.content')
                <!-- /txtdoc -->

                <hr class="blue"/>

                <!-- create -->
                @include('site.document.guestbook.blocks.index.create')
                <!-- /create -->
            </div>


            <div class="col-md-4 sidebar">
                @if (!Config::get('app.debug'))
                    <!-- sidebar -->
                    @include('site.document.text.blocks.sidebar')
                    <!-- /sidebar -->
                @endif
            </div>

            <!-- /content -->
        </div>

    </div>
@stop

