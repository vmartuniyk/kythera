<?php /* <h1 class="pull-left"><?php echo e(@$page->title); ?>123</h1>
<div class="crumb pull-right">
	<?php echo xhtml::crumbs(Router::getSelected(), ' &gt; ', false, @$item); ?>

</div>
<br class="clear"> */ ?>

<section class="content-inner__text inner-main-text">
	<h1 class="inner-main-text__title"><?php echo e(@$page->title); ?></h1>
	<p class="inner-main-text__paragraf">
		<?php echo xhtml::crumbs(Router::getSelected(), ' &gt; ', false, @$item); ?>

		<?php if(!empty($page->content)): ?>
		<p><?php echo $page->content; ?></p>
		<?php endif; ?>
	</p>
</section>