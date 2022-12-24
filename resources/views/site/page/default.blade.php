@extends('site.layout.default-new')

@section('title')
    {{ $page->title }}
@stop

@section('content')
    {{-- <div class="container">
        <div class="head">
            <h1 class="pull-left">{{ $page->title }}</h1>
          <div class="crumb pull-right">{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}</div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
            <p>
            {!! $page->content !!}
            </p>
            
            <p>
            @if (count($categories))
                This section contains {!!count($categories)!!} categories:
                <ul class="index">
                @foreach($categories as $category)
                <li><a href="{!!$category->path!!}">{!!$category->title!!}</a></li>
                @endforeach
                </ul>
            @endif
            </p>
            
        </div>
    </div> --}}


<main class="page">
    <div class="inner-page">
        <div class="inner-page__container">
            <div class="inner-page__wrap">
                <aside class="inner-page__aside aside" id="aside">
                    <nav class="aside__nav" id="aside-nav">
                        <ul class="aside__wrap">
                            <li class="aside__item item-current-page">
                                <a href="/about" class="aside__title">
                                    About Us
                                    <span></span>
                                </a>
                            </li>
                            <li class="aside__item item-current-page">
                                <a href="/contact" class="aside__title">
                                    Contact
                                    <span></span>
                                </a>
                            </li>
                            <li class="aside__item item-current-page">
                                <a href="/helpfaq" class="aside__title">
                                    Frequent Questions
                                    <span></span>
                                </a>
                            </li>
                           
                            <li class="aside__item item-current-page">
                                <a href="/newsletter-archive" class="aside__title">
                                    Newsletter
                                    <span></span>
                                </a>
                            </li>
                           
                            <li class="aside__item item-current-page">
                                <a href="#" class="aside__title">
                                    Donate
                                    <span></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>
                <div class="inner-page__content content-inner image-first-screen">
                    <div class="content-inner__image">
                        <picture><source srcset="{{ URL::asset('assets/img/item-1.webp') }}" type="image/webp">
                            <img src="{{ URL::asset('assets/img/item-1.jpg?_v=1657459303074') }}" alt="">
                        </picture>
                    </div>
                    <div class="content-inner__wrap">
                        <section class="content-inner__text inner-main-text">
                            {{-- <div class="inner-main-text__label section-label">We’re all Kytherians</div> --}}
                            <h1 class="inner-main-text__title">{{ @$page->title }}</h1>
                            <p class="inner-main-text__paragraf">

                                {!! $page->content !!}
                            </p>
                        </section>
                        {{-- <section class="about-cards">
                            <div class="about-cards__item">
                                <div class="about-cards__image">
                                    <picture><source srcset="{{ URL::asset('assets/img/item-7_1.webp') }}" type="image/webp"><img src="{{ URL::asset('assets/img/item-7_1.jpg?_v=1657459303074') }}" alt=""></picture>
                                </div>
                                <h4 class="about-cards__title">Ms. Example Woman</h4>
                                <p class="about-cards__text">
                                    Insert bio about how person is affiliated with Kythera, the website, favorite food, color, etc.
                                </p>
                            </div>
                            <div class="about-cards__item">
                                <div class="about-cards__image">
                                    <picture><source srcset="{{ URL::asset('assets/img/item-8_1.webp') }}" type="image/webp"><img src="{{ URL::asset('assets/img/item-8_1.jpg?_v=1657459303074') }}" alt=""></picture>
                                </div>
                                <h4 class="about-cards__title">Ms. Example Woman</h4>
                                <p class="about-cards__text">
                                    Insert bio about how person is affiliated with Kythera, the website, favorite food, color, etc.
                                </p>
                            </div>
                        </section>
                        <section class="thanks">
                            <h2 class="thanks__title">And A very Special Thanks to </h2>
                            <ul class="thanks__list">
                                <li class="thanks__item">Example Foundation For always being there.</li>
                                <li class="thanks__item">Example Foundation For always being there.</li>
                                <li class="thanks__item">Example Foundation For always being there.</li>
                            </ul>
                        </section> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-up-btn">
        <svg stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
            <g data-name="Group 12" transform="translate(-833.866 0.693)">
                <path data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                <line data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke-miterlimit="10" stroke-width="2" />
            </g>
        </svg>
    </div>
</main>
@stop