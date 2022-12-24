
<hr class="blue"/>
<div class="entries">
    <h2>Entries from {!!xhtml::fullname($user, false)!!}</h2>
    <p class="stats">{!!$cat_stat!!}</p>
    <ul>
        @foreach($categories as $category)
        <li>
	        <a href="{!!route('user.contact', array($entry->id, $category->document_type_id ))!!}#list">
            @if (Config::get('app.debug'))
            {!!$category->document_type_id!!}:
            @endif
			{{ $category->cat }} ({!!$category->n!!})
	        </a>
        </li>
        @endforeach
    </ul>
</div><!-- entries -->
        
        
        
@if (isset($items) && count($items))
    <div class="line"></div>

    <a name="list">&nbsp;</a>
    <h2>
    @if (Config::get('app.debug'))
    {!!$page->document_type_id!!}:
    @endif
    {!!$page->title!!}
    </h2>
    <ul class="entries">

    	@if($list == 'category')
        	@foreach($items as $i=> $item)
        	
        	
		        	<li>
		        		<div class="clearfix" style="position:relative;">
			        		<div class="pull-left category">
				        		<a href="{!!Router::getItemUrl($item)!!}">
				                @if (Config::get('app.debug'))
				                {!!$item->id!!}:
				                @endif
				        		{{ $item->title }}
				        		</a>
				        		<br/>
				        		<span class="date">{!!$item->created_at->format('d.m.Y')!!}</span>
				        		<p>{!! \Illuminate\Support\Str::words((strip_tags($item->content)), 40) !!}</p>
				        		
			        		</div>
		        		</div>
		        		<hr class="thin"/>
		        	</li>
        	    
        	
        	@endforeach
        @endif
    </ul>
@endif