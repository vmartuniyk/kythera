<?php /* <div class="txtdoc-comment-list">
    <div class="line title"></div>

    <div class="post clearfix">
        <h3>Leave a comment</h3>
        <?php echo Form::open(array('action'=>'DocumentCommentController@create', 'method'=>'POST', 'id'=>'comment')); ?>

        <textarea name="comment" class="required" placeholder="Write your comment here..."></textarea>
        <button class="btn btn-black pull-right"><?php echo trans('locale.comment.post'); ?></button>
        <?php echo Form::close(); ?>

    </div>

    <?php echo xcomment::count($item); ?>

    <?php echo xcomment::comments($item); ?>

</div> */ ?>

<?php echo Form::open(array('action'=>'DocumentCommentController@create', 'method'=>'POST', 'id'=>'comment', 'class' => 'post-comment__form')); ?>

        <div class="post-comment__field">
            <textarea name="comment" id="comment" placeholder="Enter Comment"></textarea>
        </div>
        <div class="post-comment__footer">
            <button type="submit" class="form-btn"><?php echo trans('locale.button.send'); ?></button>
        </div>
        <?php echo xcomment::count($item); ?>

        <?php echo xcomment::comments($item); ?>

<?php echo Form::close(); ?>