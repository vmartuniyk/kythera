<div class="line"></div>

{!!$page->content!!}

<div class="line"></div>

<ul class="items alpha">
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
    
    <h3><a id="{!!$letter!!}">{!!$letter!!}</a></h3>
    <hr class="gray"/>
    <ul class="items">
        @foreach($items as $item)
            <li><a href="{!!route(Router::getControllerUrl('entry'), App\Models\Translation::slug($item->name))!!}" title="{{ $item->name }}">{!!$item->name!!}</a>
            @if ($item->count)
            ({!!$item->count!!})
            @endif
            
            </li>
        @endforeach
    </ul>
    @endforeach
</div>
