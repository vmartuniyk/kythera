<!-- media -->
@if (count($items))
    <hr class="line gray mt40 clear">
    <h3 class="h3"><a href="{!!$category!!}">Video / audio</a></h3>

    @foreach($items as $i=>$item)
        @if ($i==0)
        <div class="video first">
        	<img src="{!!$item->image!!}" alt="{!!$item->title!!}">
        	<div class="overlay">
        		<h2>{!!$item->crumbs!!}</h2>
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
        	</div>
        	<a class="play audio" href="{!!$item->audio!!}" title="{!!$item->title!!}"></a>
        </div>
        @elseif ($i < count($items)-1)
        <div class="video">
        	<div class="col-md-6 w35">
        		<img src="{!!$item->image!!}" alt="{!!$item->title!!}">
        		<div class="overlay"></div>
        		<a class="play audio" href="{!!$item->audio!!}" title="{!!$item->title!!}"></a>
        	</div>
        	<div class="col-md-6 w65">
                @if (Config::get('app.debug'))
                <h2>{!!$item->id!!}:{!!$item->crumbs!!}</h2>
                @else
                <h2>{!!$item->crumbs!!}</h2>
                @endif

        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
        		<p>{!!$item->content!!}</p>
        	</div>
        	<br class="clear">
        </div>
        @else
        <div class="video last">
        	<div class="col-md-6 w35">
        		<img src="{!!$item->image!!}" alt="{!!$item->title!!}">
        		<div class="overlay"></div>
        		<a class="play audio" href="{!!$item->audio!!}" title="{!!$item->title!!}"></a>
        	</div>
        	<div class="col-md-6 w65">
        		<h2>{!!$item->crumbs!!}</h2>
        		<h3><a href="{!!$item->uri!!}" title="{!!$item->title!!}">{!!$item->title!!}</a></h3>
        		<p>{!!$item->content!!}</p>
        	</div>
        	<br class="clear">
        </div>
        @endif

    @endforeach
@endif
<!-- /media -->