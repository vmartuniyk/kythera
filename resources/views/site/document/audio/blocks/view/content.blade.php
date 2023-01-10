{{--<div class="txtdoc-view text">--}}
{{--    <div class="line"></div>--}}

{{--    <div class="txtdoc clearfix">--}}
{{--        --}}{{-- xmenu::entry_edit($item->user_id, $item) --}}
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

{{--    <div>--}}
{{--        <a class="view audio" href="{!! $item->audio !!}" title="{{ $item->title }}">--}}
{{--            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAABD0lEQVRYw+3XsQqDMBAG4L6suogIIoq7ToIuDi4i6C6+gSDo6OjjXEkhoUNibcCYtPdDFk3MB8Z4eTwwGAzG7FiWBVEUwbIsoC2QtqIo7kGGYSiceBxH8DyPIZMkAeU4MvGnfmVZqkdS3BkgSdd1rH+e56AMdxZIUlUVGzNNEyjBfQN8H+/7PijB8YDbtgknX9eVjZvnGS7H8YDkWl3XwsnJ3kj6pGkqD4zjGGhzXVeIEwFJa5qGCxiG4XXftm154BHoLFC0Pvd9l1q/lwDbtoWj598OzLKMi3AcB4G/8Yov+0i032a036iN+9UZUSwYUW7JFqx936srWI0o+WmCIND30GTEsdPogzsGg/n3PAGH4GdMo71vewAAAABJRU5ErkJggg==">--}}
{{--        </a>--}}
{{--        <div class="line"></div>--}}
{{--    </div>--}}

{{--    <p>{!!$item->content!!}</p>--}}
{{--</div>--}}


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
            <a class="view audio" href="{!! $item->audio !!}" title="{{ $item->title }}">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAABD0lEQVRYw+3XsQqDMBAG4L6suogIIoq7ToIuDi4i6C6+gSDo6OjjXEkhoUNibcCYtPdDFk3MB8Z4eTwwGAzG7FiWBVEUwbIsoC2QtqIo7kGGYSiceBxH8DyPIZMkAeU4MvGnfmVZqkdS3BkgSdd1rH+e56AMdxZIUlUVGzNNEyjBfQN8H+/7PijB8YDbtgknX9eVjZvnGS7H8YDkWl3XwsnJ3kj6pGkqD4zjGGhzXVeIEwFJa5qGCxiG4XXftm154BHoLFC0Pvd9l1q/lwDbtoWj598OzLKMi3AcB4G/8Yov+0i032a036iN+9UZUSwYUW7JFqx936srWI0o+WmCIND30GTEsdPogzsGg/n3PAGH4GdMo71vewAAAABJRU5ErkJggg==">
            </a>
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