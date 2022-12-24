<h1 class="pull-left">
	{{ @$page->title }}
	@if (isset($paginate_filter_village))
	/ {{ $paginate_filter_village->village_name }}
	@endif
</h1>
<div class="crumb pull-right">
	{!! xhtml::crumbs( Router::getSelected(), ' &gt; ', false, @$item) !!}
</div>
<br class="clear">
