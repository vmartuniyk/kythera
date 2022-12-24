<?php $__env->startSection('title'); ?>
	<?php echo e($page->title); ?> - <?php echo e($item->title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="container">

        <div class="head">
            <?php echo $__env->make('site.document.text.blocks.index.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                <?php /* xmenu::entry_edit($item->user_id, $item) */ ?>
                <?php if(Session::has('global')): ?><p class="bg-info"><?php echo Session::get('global'); ?></p><?php endif; ?>
                
                <!-- txtdoc -->
                <?php echo $__env->make('site.document.text.blocks.view.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /txtdoc -->
                <!-- comment -->
                <?php echo $__env->make('site.document.text.blocks.view.comment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /comment -->
            </div>


            <div class="col-md-4 sidebar">
                <!-- sidebar -->
                <?php if(1||!Config::get('app.debug')): ?>
					<?php echo $__env->make('site.document.text.blocks.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<?php endif; ?>
                <!-- /sidebar -->
            </div>

            <!-- /content -->
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('site.layout.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>