{{--<div class="txtdoc-view videodoc-view text">--}}
{{--    <div class="line"></div>--}}

{{--    <div class="txtdoc clearfix">--}}
{{--         xmenu::entry_edit($item->user_id, $item)--}}
{{--        <div>--}}
{{--        	<h2>--}}
{{--            @if (Config::get('app.debug'))--}}
{{--            {!!$item->id!!}:--}}
{{--            @endif--}}
{{--            {!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}--}}
{{--            </h2>--}}
{{--            {!! xmenu::author($item) !!}--}}
{{--            <h1>{{ $item->title }}</h1>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="view video"--}}
{{--        data-autoplay="true"--}}
{{--        data-title="{{ $item->title }}"--}}
{{--        data-poster="{!!$item->poster!!}"--}}
{{--        data-supplied="{!!$item->supplied!!}"--}}
{{--        {!!$item->formats!!}--}}
{{--        ></div>--}}

{{--    <p>{!!$item->content!!}</p>--}}
{{--</div>--}}

<?php
$comments = null;
$comments = $comments ?: App\Models\Comment::whereEntity($item);
$count = App\Models\Comment::whereEntityCount($item)
?>
<div class="inner-page__content content-inner entry-page">
    <div class="content-inner__wrap">
        <div class="entry-card">
            <div class="entry-card__image">

                <video width="320" height="240" controls>
                    <source src="{!!$item->supplied!!}" type="video/mp4">
                    <source src="{!!$item->supplied!!}" type="video/ogg">
                    Your browser does not support the video tag.
                </video>

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
                    <div class="entry-card__comments"><span>{{ $count }}</span> Comments</div>
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