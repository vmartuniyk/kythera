@extends('site.layout.default-new')

@section('title')
    {{ $entry->title }}
@stop

@section('content')
    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    @include('partials.front.left-front-menu')

                    @include('site.page.people.names.blocks.view.content')
                </div>
            </div>
        </div>
    </main>
@stop