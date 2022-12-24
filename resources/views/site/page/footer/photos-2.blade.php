<h5>&nbsp;</h5>

@foreach($items as $i=>$item)

  @if (isset($item->lightbox))
    <div class="col-md-4">
        <a href="{!!$item->lightbox!!}" data-lightbox="footer" data-title="{{ $item->title }}">
            <img src="{!!$item->cache!!}" alt="{{ $item->title }}" title="{{ $item->title }}"/>
        </a>
    </div>

    @if( $i % 3 == 2)
    <br class="clear"/>
    @endif

  @endif

@endforeach
