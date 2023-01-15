
<section class="post-comment">
    {!! xmessage::replies($item) !!}
    <h3 class="post-comment__title">Reply to this message</h3>

    {!! Form::open(array('action'=>'DocumentMessageController@reply', 'method'=>'POST', 'id'=>'message', 'class' => 'post-comment__form')) !!}
        <div class="post-comment__field">
            <textarea name="content" id="content" placeholder="Write your reply here..."></textarea>
        </div>
        <div class="post-comment__footer">
            <button type="submit" class="form-btn">{!! trans('locale.button.send') !!}</button>
        </div>
        {!! Form::close() !!}
</section>


@section('javascript')
    <script>
        jQuery(function() {
            var ruleSetCustom = {onfocusout: false};
            jQuery("#message").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
        });
    </script>
@stop