<section class="content-inner__text inner-main-text">
	<h1 class="inner-main-text__title">{{ @$page->title }}</h1>
	<p class="inner-main-text__paragraf">
		{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false, @$item) !!}
		@if(!empty($page->content))
		<p>{!!$page->content!!}</p>
		@endif
	</p>
</section>
