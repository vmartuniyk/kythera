
<div class="inner-page__content content-inner entry-page">
    <div class="content-inner__wrap">
        <div class="entry-card">
            <div class="entry-card__image">
                @if($item->image)
                <picture><source srcset="{{ $item->cache }}" type="image/webp">
                    <img src="{{ $item->cache }}" alt=""></picture>
                @else
                    <picture><source srcset="{{ URL::asset('assets/img/history.webp') }}" type="image/webp">
                        <img src="{{ URL::asset('assets/img/history.jpg?_v=1657459303074') }}" alt=""></picture>    
                
                @endif
                
            </div>
            <div class="entry-card__footer">
                <div class="entry-card__publication">
                    <div class="entry-card__date">
                        <time datetime="{!! $item->created_at->format('d.m.Y') !!}">{!! $item->created_at->format('d.m.Y') !!}</time> &bull;
                        <span class="entry-card__autor"> {!! $item->firstname . " ". $item->lastname !!}</span>
                    </div>

                </div>
                <div class="entry-card__activity">
                    <div class="entry-card__views">
                        <span>2,445</span> <span>Views</span>
                    </div>
                    <div class="entry-card__comments"><span>3</span> Comments</div>
                </div>
            </div>
        </div>
        <section class="content-inner__text inner-main-text">
            <h1 class="inner-main-text__title">{{ $item->title }}</h1>
            <p class="inner-main-text__paragraf">
                {!!  $item->content !!}
            </p>
            
        </section>

        @include('site.document.text.blocks.view.comment')
        <div class="show-more-btn">
            <a href="#">View Similar Entries </a>
        </div>
    </div>
</div>