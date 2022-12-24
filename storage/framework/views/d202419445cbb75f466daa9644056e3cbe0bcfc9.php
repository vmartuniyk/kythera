<?php $__env->startSection('title'); ?>
	<?php echo e($page->title); ?> - <?php echo e($item->title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php if($item): ?>
        <meta name='description' itemprop='description' content='<?php echo strip_tags($item->content); ?>' />
        <meta property='article:published_time' content='<?php echo e($item->created_at); ?>' />
        <meta property='article:section' content='event' />

        <meta property="og:description" content="<?php echo strip_tags($item->content); ?>" />
        <meta property="og:title" content="<?php echo e($item->title); ?>" />
        <meta property="og:url" content="<?php echo e(url()->current()); ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:locale" content="en-us" />
        <meta property="og:locale:alternate" content="en-us" />
        <meta property="og:site_name" content="<?php echo e(env('SITE_URL', 'kythera-family.net')); ?>" />
        <meta property="og:image" content="<?php echo e($item->image); ?>" />
        <meta property="og:image:url" content="<?php echo e($item->image); ?>" />
        <meta property="og:image:size" content="300" />

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <?php /* <div class="container">
        <div class="head">
            <?php echo $__env->make('site.document.text.blocks.index.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                <?php if(Session::has('global')): ?><p class="bg-info"><?php echo Session::get('global'); ?></p><?php endif; ?>
                
                <!-- txtdoc -->
                <?php echo $__env->make('site.document.image.blocks.view.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /txtdoc -->
                <!-- comment -->
                <?php echo $__env->make('site.document.text.blocks.view.comment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /comment -->
            </div>


            <div class="col-md-4 sidebar">
                <!-- sidebar -->
                <?php echo $__env->make('site.document.text.blocks.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /sidebar -->
            </div>

            <!-- /content -->
        </div>

    </div> */ ?>

    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    <?php echo $__env->make('site.document.image.blocks.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                    <?php echo $__env->make('site.document.image.blocks.view.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('site.layout.default-new', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>