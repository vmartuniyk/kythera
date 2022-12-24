<div class="txtdoc-view text">
    <div class="line"></div>

    <div class="txtdoc clearfix">
        <?php /* xmenu::entry_edit($item->user_id, $item) */ ?>
        <div>
        	<h2>
            <?php if(Config::get('app.debug')): ?>
            <?php echo $item->id; ?>:
            <?php endif; ?>
            <?php echo xhtml::crumbs(Router::getSelected(), ' &gt; ', false); ?>

            </h2>
            <?php /* <p class="author"><?php echo trans('locale.submitted', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))); ?></p> */ ?>
            <?php echo xmenu::author($item); ?>

            <h1><?php echo e($item->title); ?></h1>
        </div>
    </div>
<?php //echo '<pre>'; print_r($item); die;?>
    <?php if($item->image): ?>
        <div class="documentTextImage">
            <?php if( !empty($item->lightbox) ): ?>
                <a href="<?php echo $item->lightbox; ?>" data-lightbox="<?php echo $item->title; ?>" data-title="<?php echo $item->title; ?>">
                    <img alt="<?php echo $item->title; ?>" src="<?php echo $item->image; ?>" />
                </a>
            
            <?php endif; ?>
            <?php if( !empty($item->copyright) ): ?>
                <br/><span class="copyright"><?php echo $item->copyright; ?></span>
            <?php endif; ?>
            <div class="line"></div>
        </div>
    <?php endif; ?>

    <p><?php echo $item->content; ?></p>
</div>