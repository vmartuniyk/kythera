



<div class="inner-articles__top">
    <h3 class="inner-articles__title">All entries</h3>
    <div class="inner-articles__sort-menu sort-menu-inner">
        <div class="sort-menu-inner__text"><?php echo trans('locale.filter.sortedby'); ?>:</div>
        <div class="sort-menu-inner__select">
            
                <form method="get" action="<?php echo URL::full(); ?>">
                    
                    <?php /* <select class="sort-menu-inner__enter-field auto-submit" name="po">
                        <?php echo e($i = 1); ?>

                        <?php foreach($paginate_orders as $order): ?>
                        <option value="<?php echo e($i); ?>" class="sort-menu-inner__item"> <?php echo e($order); ?> </option>
                         <?php echo e($i++); ?>   
                        <?php endforeach; ?>
                        
            
                    </select> */ ?>
                   
                    <?php echo Form::select('po', $paginate_orders, Session::get('paginate_order'), array('class'=>'sort-menu-inner__enter-field auto-submit')); ?>

                </form>
                <?php /* <li class="sort-menu-inner__item">The most popular</li>
                <li class="sort-menu-inner__item">Most Recent</li>
                <li class="sort-menu-inner__item">By date</li> */ ?>
            <?php /* </ul> */ ?>
        </div>
    </div>
</div>

<section class="content-inner__articles inner-articles">
    <div class="inner-articles__cards">
        <?php foreach($items as $i=>$item): ?>

            <article class="inner-articles__card card-articles">
                <div class="card-articles__image">
                    
                    <?php if($item->image): ?>
                        <picture><source srcset="<?php echo e($item->cache); ?>" type="image/webp">
                            <img src="<?php echo e($item->cache); ?>" alt=""></picture>
                    <?php else: ?>
                        <picture><source srcset="<?php echo e(URL::asset('assets/img/history.webp')); ?>" type="image/webp">
                            <img src="<?php echo e(URL::asset('assets/img/history.jpg?_v=1657459303074')); ?>" alt=""></picture>    
                    
                    <?php endif; ?>
                    
                </div>
                <div class="card-articles__info">
                    <h4 class="card-articles__title"><?php echo $item->title; ?></h4>
                    <p class="card-articles__text">
                        <?php echo str_limit(strip_tags($item->content)); ?>

                    </p>
                    <a href="<?php echo $item->uri; ?>" class="card-articles__link view-link">
                        View Full Message
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.132 16.693">
                            <g data-name="Group 23" transform="translate(-1292.865 -1328.645)">
                                <g data-name="Group 22">
                                    <path data-name="Path 40" d="M1307.614,1328.991l7.691,8-7.691,8" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                                </g>
                                <line data-name="Line 3" x1="21.613" transform="translate(1292.865 1336.991)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
                            </g>
                        </svg>
                    </a>
                    <div class="card-articles__footer">
                        <div class="card-articles__date">
                            <time datetime="<?php echo $item->created_at->format('d.m.Y'); ?>"><?php echo $item->created_at->format('d.m.Y'); ?></time> &bull;
                            <span class="card-articles__autor"><?php echo $item->firstname . " ". $item->lastname; ?></span>
                        </div>
                        <span class="card-articles__description">
                            <?php echo $item->crumbs; ?>

                        </span>
                    </div>
                </div>
            </article>
       
        <?php endforeach; ?>
    </div>
    <span class="pull-pagination"><?php echo $pages->render(); ?></span>
    <?php /* <div class="inner-articles__show-more show-more-btn">
        <button type="button">View More Entries</button>
    </div> */ ?>
</section>


<?php $__env->startSection('js'); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<?php $__env->stopSection(); ?>