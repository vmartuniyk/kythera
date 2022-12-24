<!-- message board -->
@if (count($items))
    <hr class="line gray clear">
    <h3 class="h3"><a href="{!!$category!!}">message board</a></h3>
    
    @foreach($items as $i=>$item)
    <div class="message">
        @if (Config::get('app.debug'))
        <p class="date">{!!$item->id!!}:{!!$item->date!!} ({!!$item->crumbs!!})</p>
        @else
        <p class="date">{!!$item->date!!} ({!!$item->crumbs!!})</p>
        @endif
        <h4><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->author!!}</a></h4>
        <p>{!!$item->content!!}</p>
    </div>
    @endforeach
@endif
<!-- /message board -->