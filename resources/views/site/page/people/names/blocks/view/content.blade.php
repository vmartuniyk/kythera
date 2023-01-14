<div class="inner-page__content content-inner entry-page">
	<div class="content-inner__wrap">
		@include('site.document.text.blocks.index.head')

		<section class="content-inner__text inner-main-text">
			<h1 class="inner-main-text__title">{{ $entry->name }}</h1>
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

		</section>

	</div>
</div>
