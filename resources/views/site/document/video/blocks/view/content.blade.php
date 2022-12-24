<div class="txtdoc-view videodoc-view text">
    <div class="line"></div>

    <div class="txtdoc clearfix">
        {{-- xmenu::entry_edit($item->user_id, $item) --}}
        <div>
        	<h2>
            @if (Config::get('app.debug'))
            {!!$item->id!!}:
            @endif
            {!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
            </h2>
            {!! xmenu::author($item) !!}
            <h1>{{ $item->title }}</h1>
        </div>
    </div>

    <div class="view video"
        data-autoplay="true"
        data-title="{{ $item->title }}"
        data-poster="{!!$item->poster!!}"
        data-supplied="{!!$item->supplied!!}"
        {!!$item->formats!!}
        ></div>

    <p>{!!$item->content!!}</p>
</div>