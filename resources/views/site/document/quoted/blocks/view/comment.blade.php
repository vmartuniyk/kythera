<div class="txtdoc-comment-list">
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
</div>

@section('javascript')
<script>
	jQuery(function() {
		var ruleSetCustom = {onfocusout: false};
		jQuery("#comment").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop