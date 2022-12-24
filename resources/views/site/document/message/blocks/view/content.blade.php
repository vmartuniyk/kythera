<div class="txtdoc-view text">
    <div class="msgdoc clearfix">
    	{{-- xmenu::entry_edit($item->user_id, $item) --}}

        <h1>
            @if (Config::get('app.debug'))
            {!!$item->id!!}:
            @endif
            {{ $item->title }}
        </h1>
        {!! xmenu::author($item) !!}
    </div>
    <br/>
    <p>{!!$item->content!!}</p>
</div>