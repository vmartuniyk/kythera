@extends('site.layout.default-new')

@section('title')
    {{ $page->title }}
@stop



@section('content')
    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    @include('partials.front.left-front-menu')

                    <div class="inner-page__content content-inner text-first-screen">
                        <div class="content-inner__wrap">
                            @include('site.document.text.blocks.index.head')

                            <section class="content-inner__articles inner-articles">
                                @include('site.document.audio.blocks.index.content')
                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop