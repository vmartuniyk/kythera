@extends('site.layout.default-new')

@section('title')
	{{ $page->title }} - {{ $item->title }}
@stop

@section('style')
@stop


@section('content')
{{--    <div class="container">--}}

{{--        <div class="head">--}}
{{--            @include('site.document.text.blocks.index.head')--}}
{{--        </div>--}}

{{--        <div class="content">--}}
{{--            <!-- content -->--}}

{{--            <div class="col-md-8 col2">--}}
{{--                --}}{{-- xmenu::entry_edit($item->user_id, $item) --}}
{{--                @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif--}}
{{--                --}}
{{--                <!-- txtdoc -->--}}
{{--                @include('site.document.text.blocks.view.content')--}}
{{--                <!-- /txtdoc -->--}}
{{--                <!-- comment -->--}}
{{--                @include('site.document.text.blocks.view.comment')--}}
{{--                <!-- /comment -->--}}
{{--            </div>--}}


{{--            <div class="col-md-4 sidebar">--}}
{{--                <!-- sidebar -->--}}
{{--                @if (1||!Config::get('app.debug'))--}}
{{--					@include('site.document.text.blocks.sidebar')--}}
{{--				@endif--}}
{{--                <!-- /sidebar -->--}}
{{--            </div>--}}

{{--            <!-- /content -->--}}
{{--        </div>--}}

{{--    </div>--}}
<main class="page">
    <div class="inner-page">
        <div class="inner-page__container">
            <div class="inner-page__wrap">
                @include('partials.front.left-front-menu')

                @include('site.document.text.blocks.view.content')

            </div>
        </div>
    </div>
</main>
@stop