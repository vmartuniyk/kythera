
<div class="inner-page__content content-inner entry-page">
    <div class="content-inner__wrap">
        <div class="entry-card">
            <div class="entry-card__image">
                <?php if($item->image): ?>
                <picture><source srcset="<?php echo e($item->cache); ?>" type="image/webp">
                    <img src="<?php echo e($item->cache); ?>" alt=""></picture>
                <?php else: ?>
                    <picture><source srcset="<?php echo e(URL::asset('assets/img/history.webp')); ?>" type="image/webp">
                        <img src="<?php echo e(URL::asset('assets/img/history.jpg?_v=1657459303074')); ?>" alt=""></picture>    
                
                <?php endif; ?>
                
            </div>
            <div class="entry-card__footer">
                <div class="entry-card__publication">
                    <div class="entry-card__date">
                        <time datetime="<?php echo $item->created_at->format('d.m.Y'); ?>"><?php echo $item->created_at->format('d.m.Y'); ?></time> &bull;
                        <span class="entry-card__autor"> <?php echo $item->firstname . " ". $item->lastname; ?></span>
                    </div>

                </div>
                <div class="entry-card__activity">
                    <div class="entry-card__views">
                        <span>2,445</span> <span>Views</span>
                    </div>
                    <div class="entry-card__comments"><span>3</span> Comments</div>
                </div>
            </div>
        </div>
        <section class="content-inner__text inner-main-text">
            <h1 class="inner-main-text__title"><?php echo e($item->title); ?></h1>
            <p class="inner-main-text__paragraf">
                <?php echo $item->content; ?>

            </p>
            
        </section>

        <?php echo $__env->make('site.document.text.blocks.view.comment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="show-more-btn">
            <a href="#">View Similar Entries</a>
        </div>
    </div>
</div>