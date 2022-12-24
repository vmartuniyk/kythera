
<section class="post-comment">
    <h3 class="post-comment__title">Leave A comment Message Board Form</h3>

    <?php echo Form::open(array('action'=>'DocumentCommentController@create', 'method'=>'POST', 'id'=>'message', 'class' => 'post-comment__form')); ?>

        <div class="post-comment__field">
            <textarea name="comment" id="comment" placeholder="Enter Comment"></textarea>
        </div>
        <div class="post-comment__footer">
            <button type="submit" class="form-btn"><?php echo trans('locale.button.send'); ?></button>
        </div>
        <?php echo Form::close(); ?>

</section>