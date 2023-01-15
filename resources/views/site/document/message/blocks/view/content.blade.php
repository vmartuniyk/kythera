<?php
$comments = null;
$comments = $comments ?: App\Models\Comment::whereEntity($item);
$count = App\Models\Comment::whereEntityCount($item)
?>
<div class="inner-page__content content-inner entry-page">
    <div class="content-inner__wrap">

        <h1 class="inner-main-text__title">{{ @$page->title }}</h1>

        <div class="entry-card">

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

        @include('site.document.message.blocks.view.comment')

    </div>
</div>