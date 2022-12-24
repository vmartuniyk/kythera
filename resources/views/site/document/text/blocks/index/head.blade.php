{{-- <h1 class="pull-left">
	{{ @$page->title }}
	@if (isset($paginate_filter_village))
	/ {{ $paginate_filter_village->village_name }}
	@endif
</h1>
<div class="crumb pull-right">
	{!! xhtml::crumbs( Router::getSelected(), ' &gt; ', false, @$item) !!}
</div>
<br class="clear"> --}}

<section class="content-inner__text inner-main-text">
	<h1 class="inner-main-text__title">{{ @$page->title }}</h1>
	<p class="inner-main-text__paragraf">
		{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false, @$item) !!}
		@if(!empty($page->content))
		<p>{!!$page->content!!}</p>
		@endif
	</p>
</section>
