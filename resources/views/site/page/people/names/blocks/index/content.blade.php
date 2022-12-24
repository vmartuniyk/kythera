{{-- <div class="line"></div>

@if ($page->content)
	{!!$page->content!!}
	<div class="line"></div>
@endif


<ul id="alpha" class="items alpha">
    @foreach($names as $letter => $items)
        <li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
    @endforeach
    <br class="clear"/>
</ul>


<div class="col-md-4 names">
    @foreach($names as $letter => $items)
    @if ((in_array($letter, array('I', 'P', 'Λ', 'Τ'))))
        </div>
        <div class="col-md-4 names">
    @endif

    <h3><a id="{!!$letter!!}" class="letter">{!!$letter!!}</a><a class="back" href="#alpha"><i class="glyphicon glyphicon-menu-up"></i></a></h3>
    <hr class="gray"/>
    <ul class="items">
        @foreach($items as $item)
            <li>
            
            @if (Config::get('app.debug'))
               	{!!$item->id!!})
            @endif

            <a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->name))!!}" title="{{ $item->name }}">{!!$item->name!!}</a>
            @if ($item->count)
            ({!!$item->count!!})
            @endif
            </li>
        @endforeach
    </ul>
    @endforeach
</div> --}}

<div class="inner-page__content content-inner text-first-screen">
    <div class="content-inner__wrap">
        <section class="content-inner__text inner-main-text">
            <div class="inner-main-text__label section-label">{{ $page->title }}</div>
            <h1 class="inner-main-text__title">{{ $page->title }}</h1>
            <p class="inner-main-text__paragraf">
    
                {!! $page->content !!}
            </p>
        </section>
           

        <div class="names">
            <ul id="alpha" class="names__letters">
                    @foreach($names as $letter => $items)
                        <li><a href="#{!!$letter!!}">{!!$letter!!}</a></li>
                    @endforeach
            </ul>
            
            <div class="names__wrap">
                @foreach($names as $letter => $items)
                <div class="names__column column-names">
                    <div class="column-names__top">
                        <div class="column-names__letter">{!!$letter!!} </div>
                    </div>
                    @foreach($items as $item)
                    <ul class="column-names__list">
                        <li class="column-names__item">
                            @if ($item->count)
                            <span class="column-names__number">
                                <a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->name))!!}" title="{{ $item->name }}">{!!$item->name!!}</a>
                            </span>
                                ({!!$item->count!!})
                            @else
                                <span class="column-names__name">{!!$item->name!!}</span>
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