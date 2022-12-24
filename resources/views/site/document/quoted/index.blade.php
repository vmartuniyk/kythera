@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
@stop

@section('content')
    <div class="container">

        <div class="head">
            @include('site.document.text.blocks.index.head')
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                <!-- txtdoc -->
                @include('site.document.text.blocks.index.content')
                <!-- /txtdoc -->
            </div>


            <div class="col-md-4 sidebar">
                @if (1||!Config::get('app.debug'))
                    <!-- sidebar -->
                    @include('site.document.text.blocks.sidebar')
                    <!-- /sidebar -->
                @endif
            </div>

            <!-- /content -->
        </div>

    </div>
@stop