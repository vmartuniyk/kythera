<div class="txtdoc-view text">
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

    <p>{!!$item->content!!}</p>
    
    @if (!empty(trim($item->source)))
    <p>Source: {!!$item->source!!}</p>
    @endif
</div>