<div class="txtdoc-view text">
    <div class="line"></div>

    <div class="xtxtdoc clearfix">
            <h1>
	            @if (Config::get('app.debug'))
	            {!!$item->id!!}:
	            @endif
	            &gt; {{ $item->title }} ({!!$total!!})
            </h1>
    </div>

    <p>{!!$item->content!!}</p>

    <ul class="villages">
    @foreach($categories as $category)
    	<li>
	    	<?php $page  = $category['route']->page; ?>
	    	<?php $count = $category['count']; ?>

            @if ($count)
	            {!! xhtml::crumbs($page, ' &gt; ', true, null, ['v='.$item->id] )!!} ({!!$count!!})
            @else
            	{{ $page->title }}
            @endif
        </li>
    @endforeach
    </ul>

    {{--
    <div class="line"></div>
    <a data-title="Kythera map" data-lightbox="map" href="/xhtml/img/map.png">
    	<img src="/xhtml/img/map.png">
    </a>
    --}}
</div>