@if (count($items))
    
    <hr class="line gray clear">
    <h3 class="h3"><a href="{!!$category!!}">{!!$title!!}</a></h3>
    
    @foreach($items as $i=>$item)
        <div class="item">
           <div class="col-md-6 w35">
               <div>
                    <a href="{!!$item->uri!!}" title="{!!$item->title!!}">
                    <img src="{!!$item->image!!}" alt="{!!$item->title!!}">
                    </a>
               </div>
           </div>
           <div class="col-md-6 w65">
                @if (Config::get('app.debug'))
                <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                @else
                <h2>{!!$item->crumbs!!}</h2>
                @endif
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
           </div>
           <br class="clear">
        </div>
    @endforeach
    
@endif