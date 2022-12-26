{{-- <div class="txtdoc-comment-list">
    <div class="line title"></div>

    <div class="post clearfix">
        <h3>Leave a comment</h3>
        {!! Form::open(array('action'=>'DocumentCommentController@create', 'method'=>'POST', 'id'=>'comment')) !!}
        <textarea name="comment" class="required" placeholder="Write your comment here..."></textarea>
        <button class="btn btn-black pull-right">{!! trans('locale.comment.post') !!}</button>
        {!! Form::close() !!}
    </div>

    {!! xcomment::count($item) !!}
    {!! xcomment::comments($item) !!}
</div> --}}
@include('partials.front.comments-section',['item' => $item])

<section class="post-comment">
    <h3 class="post-comment__title">Leave A comment</h3>
    {!! Form::open(array('action'=>'DocumentCommentController@create', 'method'=>'POST', 'id'=>'comment', 'class' => 'post-comment__form')) !!}
            <div class="post-comment__field">
                <textarea name="comment" id="comment" placeholder="Enter Comment"></textarea>
            </div>
            <div class="post-comment__footer">
                <button type="submit" class="form-btn">{!! trans('locale.button.send') !!}</button>
            </div>
    {!! Form::close() !!}
</section>