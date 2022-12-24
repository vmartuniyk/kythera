<!-- recent posts -->
@if (count($items))
    <h3 class="h3" style="margin-top:22px">Recent posts</h3>
    
    @foreach($items as $i=>$item)
        <div class="col-xs-6 col-lg-6">
           <div class="post {!! $i%2?'pull-right':'' !!}">
    			<!--165/105-->
    			<div>
    				<a href="{!!$item->uri!!}" title="{!!$item->title!!}">
    					<img src="{!!$item->image!!}" alt="{!!$item->title!!}" />
    				</a>
    			</div>
    			<div class="text">
                    @if (Config::get('app.debug'))
                    <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                    @else
                    <h2>{!!$item->crumbs!!}</h2>
                    @endif
	               <h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{{ $item->title }}</a></h3>
	               <p>{!!$item->content!!}</p>
               </div>
               
            </div>
        </div>
        {!! $i<2&&$i%2?'<br style="clear:both" />':'' !!}
    @endforeach
    
@endif
<!-- /recent posts -->