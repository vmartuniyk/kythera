<?php $__env->startSection('content'); ?>
    <?php /* <div class="container">

        <div class="head">
            
        </div>

        <div class="content">
            <!-- content -->

            <div class="col-md-8 col2">
                <!-- txtdoc -->
                <?php echo $__env->make('site.document.image.blocks.index.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /txtdoc -->
            </div>


            {{-- <div class="col-md-4 sidebar">
                <!-- sidebar -->
                <?php echo $__env->make('site.document.text.blocks.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /sidebar -->
            </div> */ ?>

            <!-- /content -->
        <?php /* </div>

    </div>  */ ?>
    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    <?php echo $__env->make('partials.front.left-front-menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                    <div class="inner-page__content content-inner text-first-screen">
                        <div class="content-inner__wrap">
                            <?php echo $__env->make('site.document.image.blocks.index.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                            <section class="content-inner__articles inner-articles">
                                <?php echo $__env->make('site.document.image.blocks.index.content', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('site.layout.default-new', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>