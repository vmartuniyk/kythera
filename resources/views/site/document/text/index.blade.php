@extends('site.layout.default-new')

@section('title')
    {{ $page->title }}
@stop

@section('style')
@stop

@section('content')
    {{-- <div class="container">

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

    </div> --}}

    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    @include('partials.front.left-front-menu')

                    <div class="inner-page__content content-inner text-first-screen">
                        <div class="content-inner__wrap">
                            @include('site.document.text.blocks.index.head')

                            <section class="content-inner__articles inner-articles">
                                @include('site.document.text.blocks.index.content')
                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop