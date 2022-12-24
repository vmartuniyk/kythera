@extends('site.layout.default-new')

@section('title')
    {{ $page->title }}
@stop


@section('content')
    {{-- <div class="container">

        <div class="head">
            @include('site.document.text.blocks.index.head')
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                @include('site.page.village.blocks.index.content')
            </div>

            

            <!-- /content -->
        </div>

    </div> --}}
    <div class="inner-page">
        <div class="inner-page__container">
            <div class="inner-page__wrap">

                @include('partials.front.left-front-menu')

                <div class="inner-page__content content-inner text-first-screen">
                    <div class="content-inner__wrap">
                        <section class="content-inner__text inner-main-text">
                            <div class="inner-main-text__label section-label">{{ $page->title }}</div>
                            <p class="inner-main-text__paragraf">
                    
                                {!! $page->content !!}
                            </p>
                        </section>
                           

                        <div class="names">
                            <ul id="alpha" class="names__letters">
                                    @foreach($villages as $letter => $items)
                                        <li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
                                    @endforeach
                            </ul>
                            
                            <div class="names__wrap">
                                @foreach($villages as $letter => $items)
                                <div class="names__column column-names">
                                    <div class="column-names__top">
                                        <div class="column-names__letter">{!!$letter!!} </div>
                                    </div>
                                    @foreach($items as $item)
                                    <ul class="column-names__list">
                                        <li class="column-names__item">
                                            @if ($item->count)
                                            <span class="column-names__number">
                                                <a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->village_name))!!}" title="{{ $item->village_name }}">{!!$item->village_name!!}</a>
                                            </span>
                                                ({!!$item->count!!})
                                            @else
                                                <span class="column-names__name">{!!$item->village_name!!}</span>
                                            @endif
                                    
                                            
                                        </li>
                                    </ul>
                                    @endforeach
                                </div>
                                    
                                @endforeach
                            </div>
                            
                    
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop



