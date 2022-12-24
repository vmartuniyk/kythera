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
                        <section class="content-inner__text inner-main-text">
                            {{-- <div class="inner-main-text__label section-label">Subcategory Subhead</div> --}}
                            <h1 class="inner-main-text__title">{{ $page->title }}</h1>
                            <p class="inner-main-text__paragraf">
                                <li><a href="{!!action('LatestPostsController@getIndex', array('text'))!!}">Show all documents</a></li>
                                <li><a href="{!!action('LatestPostsController@getIndex', array('comment'))!!}">Show comments</a></li>
                                <li><a href="{!!action('LatestPostsController@getIndex', array('image'))!!}">Show image documents</a></li>
                                
                            </p>
                        </section>
                        <section class="content-inner__articles inner-articles">
                            <div class="inner-articles__cards">
                                @foreach($items as $i=>$item)

                                    <article class="inner-articles__card card-articles">
                                        <div class="card-articles__image">
                                            
                                            @if($item->image)
                                                <picture><source srcset="{{ $item->cache }}" type="image/webp">
                                                    <img src="{{ $item->cache }}" alt=""></picture>
                                            @else
                                                <picture><source srcset="../assets/img/history.webp" type="image/webp">
                                                    <img src="img/history.jpg?_v=1657459303074" alt=""></picture>    
                                            
                                            @endif
                                            
                                        </div>
                                        <div class="card-articles__info">
                                            <h4 class="card-articles__title">{!!$item->title!!}</h4>
                                            <p class="card-articles__text">
                                                {!! str_limit(strip_tags($item->content)) !!}
                                            </p>
                                            <a href="{!!$item->uri!!}" class="card-articles__link view-link">
                                                View Full Message
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.132 16.693">
                                                    <g data-name="Group 23" transform="translate(-1292.865 -1328.645)">
                                                        <g data-name="Group 22">
                                                            <path data-name="Path 40" d="M1307.614,1328.991l7.691,8-7.691,8" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                                                        </g>
                                                        <line data-name="Line 3" x1="21.613" transform="translate(1292.865 1336.991)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                                                    </g>
                                                </svg>
                                            </a>
                                            <div class="card-articles__footer">
                                                <div class="card-articles__date">
                                                    <time datetime="{!! $item->created_at->format('d.m.Y') !!}">{!! $item->created_at->format('d.m.Y') !!}</time> &bull;
                                                    <span class="card-articles__autor">{!! $item->firstname . " ". $item->lastname !!}</span>
                                                </div>
                                                <span class="card-articles__description">
                                                    {!! $item->crumbs !!}
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                               
                                @endforeach
                            </div>
                            <span class="pull-pagination">{!! $pages->render() !!}</span>
                            {{-- <div class="inner-articles__show-more show-more-btn">
                                <button type="button">View More Entries</button>
                            </div> --}}
                        </section>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


@stop



