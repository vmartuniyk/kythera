<div class="line"></div>

<h1>&gt; {{ $entry->name }}</h1>

<ul class="names">
@foreach($categories as $category)
	<li>
		@if ($category->page)
		{!! xhtml::crumbs( $category->page, ' &gt; ', true, null, ['n='.$entry->id] ) !!} ({!!$category->docCount!!})
		@else
		{{--
		{!!$category->docTypeID!!} ({!!$category->docCount!!})
		--}}
		@endif
	</li>
@endforeach
</ul>
