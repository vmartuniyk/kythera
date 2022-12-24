@extends('site.layout.default')

@section('title')
    Edit comment
@stop

@section('style')
.content #comment textarea {height:120px}
@stop

@section('content')
<div class="container">
    <div class="head force-left">
      <h1 class="pull-left">Edit comment</h1>
      <div class="crumb pull-right">Home > <span>Edit comment</span></div>
        <br class="clear"/>
    </div>
    <hr class="thin"/>
    <div class="content">
    			{{--
                <div class="form-error">
                    {!! HTML::ul($errors->all()) !!}
                </div>
                {!!print_r($errors,1)!!}
                --}}
                <div class="form-error">
                    @if(Session::has('global'))
                    {!! Session::get('global') !!}
                    @endif
                </div>

                <h2>{!!$entry->title!!}</h2>
                <p>{!!$entry->content!!}</p>

			    <div class="line title"></div>


		        {!! Form::open(array('action'=>array('DocumentCommentController@update', $comment->id), 'method'=>'PUT', 'id'=>'comment')) !!}
		        <div class="form-group">
		        	<label class="control-label">Edit comment</label>
		        	<textarea name="comment" class="form-control required" placeholder="Write your comment here...">{{ $comment->comment }}</textarea>
		        </div>
		        <hr class="thin"/>
		        <div class="form-group">
		        	<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
		        	<button class="btn btn-black pull-right">{!! trans('locale.button.update') !!}</button>
		        </div>
		        {!! Form::close() !!}
    </div>
</div><!-- container -->
@stop


@section('javascript')
<script>
	jQuery(function() {
		var ruleSetCustom = {};
		jQuery("#comment").validate(jQuery.extend({}, ruleSetDefault, ruleSetCustom));
    });
</script>
@stop
