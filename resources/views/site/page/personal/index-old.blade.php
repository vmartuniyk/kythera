@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
.content.personal .stats {margin:0;font: 12px/12px arial;font-weight:700}
.content.personal ul {list-style:none; padding-left:0}
.content.personal ul.entries li div:first-child {padding:10px 0}
.content.personal ul.entries li div.actions {width:200px;padding:10px 0}
.content.personal ul.entries li a.enable {color:orange}
.content.personal ul.entries li span.date {xcolor: #cccccc;font: 12px/12px arial;}
.content.personal ul.entries li hr.thin {margin:0}
.content.personal ul.entries li.hover {background-color: #efefef;}

.content.personal ul.entries li .comment {width:70%}
@stop


@section('content')
<div class="container">

    <div class="head">
        <h1 class="pull-left">
        Welcome
        {{ Auth::user()->full_name }}
        @if (Auth::user()->isAdmin())
         (administrator)
        @endif
        </h1>
        <div class="crumb pull-right">
        	{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
        	{{-- @francesdath 2017-06-13 link to profile edit page --}}
        	> <a href="{!!action('PersonalPageController@edit')!!}">{!! trans( 'user.personal.edit.title' ) !!} <i class="glyphicon glyphicon-pencil create"></i></a>
        </div>
    </div>
    <br class="clear"/>

    <hr class="thin"/>
    <div class="content personal">
	    @if(!empty($page->content))
	    <p>{!!$page->content!!}</p>
	    <br/>
	    <div class="line"></div>
	    @endif

        <div class="form-success">
            @if(Session::has('global'))
            {!! Session::get('global') !!}
            @endif
        </div>

		<div class="row">
	        <div class="col-md-4">
		        <h2>Your entries</h2>
		        <p class="stats">{!!$cat_stat!!}</p>
		        <ul>
			        @foreach($categories as $category)
			        <li>
				        <a href="{!!route('site.page.your.personal.page.category', $category->document_type_id)!!}#list">
			            @if (Config::get('app.debug'))
			            {!!$category->document_type_id!!}:
			            @endif
						{{ $category->cat }} ({!!$category->n!!})
				        </a>
			        </li>
			        @endforeach
		        </ul>
	        </div>

	        <div class="col-md-4">
	        <h2>Your comments</h2>
		        <p class="stats">{!!$com_stat!!}</p>
		        <ul>
			        @foreach($comments as $comment)
						@if(isset($comment->category))
						<li>
							<a href="{!!route('site.page.your.personal.page.comment', $comment->document_type_id)!!}#list">
							@if (Config::get('app.debug'))
							{!!$comment->document_type_id!!}:
							@endif
							{{ $comment->category->title }} ({{ $comment->n }})
							</a>
						</li>
						@endif
			        @endforeach
		        </ul>
   	        </div>

	        <div class="col-md-4">
				<h2>Your family trees</h2>
				<p class="stats">{!!$person_stat!!}</p>
				<ul>
					@foreach($persons as $person)
					<li>
						<a href="{!!action('DocumentPersonController@edit', $person->entry_id)!!}">
						@if (Config::get('app.debug'))
						{!!$person->id!!}:
						@endif
						{{ $person->displayname }}
						</a>

                        <span class="stats">{!!$person->invited ? '(Invited)' : ''!!}</span>
					</li>
					@endforeach
				</ul>
			</div>

		</div>

        @if (isset($items) && count($items))
        	<hr class="blue"/>

	        <a name="list">&nbsp;</a>
	        <h2>
	        @if (Config::get('app.debug'))
	        {!!$page->document_type_id!!}:
	        @endif
	        {!!$page->title!!}
	        </h2>
	        <ul class="entries">

	        	@if($list == 'category')
		        	@foreach($items as $item)
		        	<li>
		        		<div class="clearfix" style="position:relative;">
			        		<div class="pull-left category">
				        		<a href="{!!Router::getItemUrl($item)!!}">
				                @if (Config::get('app.debug'))
				                {!!$item->id!!}:
				                @endif
				        		{{ $item->title }}
				        		</a>
				        		<br/>
				        		<span class="date">{!!$item->created_at->format('d.m.Y')!!}</span>
			        		</div>


			        		<div class="toolbar">
			        			<a class="btn btn-default btn-xs" href="{!!action('EntryController@edit', $item->id)!!}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
			        			@if ($item->enabled)
			        			<a class="btn btn-default btn-xs " href="{!!action('EntryController@enable', array($item->id, 0))!!}"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable</a>
			        			@else
			        			<a class="btn btn-default btn-xs " href="{!!action('EntryController@enable', array($item->id, 1))!!}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Enable</a>
			        			@endif

					            @if (Auth::check() && Auth::user()->isAdmin())
   					            	@if ($item->top_article)
					            		<a class="btn btn-default btn-xs" href="{!!action('EntryController@promote', array($item->id, 'degrade')) !!}"><span class="glyphicon glyphicon-star blue" aria-hidden="true"></span> Top article</a>
					            	@else
					            		<a class="btn btn-default btn-xs " href="{!!action('EntryController@promote', array($item->id, 'promote')) !!}"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Top article</a>
					            	@endif

					            @endif

								{{--
			        			@if (Config::get('app.entity.delete', false))
			        			<a class="btn btn-danger delete" href="{!!action('EntryController@destroy', $item->id)!!}">&raquo; Delete</i></a>
			        			@endif
			        			--}}
			        		</div>


		        		</div>
		        		<hr class="thin"/>
		        	</li>
		        	@endforeach
	        	@endif


	        	@if($list == 'comment')
		        	@foreach($items as $i=>$item)
		        	<li>
		        		<div class="clearfix" style="position:relative;">
			        		<div class="pull-left comment">
			        			<a href="{!!Router::getItemUrl($entries[$i])!!}">
				                @if (Config::get('app.debug'))
				                {!!$entries[$i]->id!!}:
				                @endif
				                {{ $entries[$i]->title }}
				                </a>
				                <br/>
				                <span class="date">{!!$item->created_at->format('d.m.Y')!!}</span>
				                <br/>
				                @if (Config::get('app.debug'))
				                {!!$item->id!!}:
				                @endif
				                {{ $item->comment }}
			        		</div>

			        		<div class="toolbar">
			        			<a class="btn btn-default btn-xs" href="{!!action('DocumentCommentController@edit', $item->id)!!}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
			        			@if ($item->enabled)
			        			<a class="btn btn-default btn-xs " href="{!!action('DocumentCommentController@enable', array($item->id, 0))!!}"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable</a>
			        			@else
			        			<a class="btn btn-default btn-xs " href="{!!action('DocumentCommentController@enable', array($item->id, 1))!!}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Enable</a>
			        			@endif


								{{--
			        			@if (Config::get('app.entity.delete', false))
			        			<a class="btn btn-danger delete" href="{!!action('EntryController@destroy', $item->id)!!}">&raquo; Delete</i></a>
			        			@endif
			        			--}}
			        		</div>

		        		</div>
		        		<hr class="thin"/>
		        	</li>
		        	@endforeach
	        	@endif



	        </ul>
        @endif

    </div>

</div>

	<!-- confirm delete dialog -->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(array('method'=>'DELETE')) !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">{title}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('locale.button.cancel') }}</button>
                        <button type="submit" class="btn btn-danger danger">{{ trans('locale.button.delete') }}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop


@section('javascript')
<script>
	jQuery( "ul.entries li" ).hover(function() {
		jQuery(this).addClass( "hover" );
	}, function() {
		jQuery(this).removeClass( "hover" );
	});
</script>

<script>
	jQuery( "a.delete" ).on("click", function(e)
	{
		e.preventDefault();
		jQuery.ajax({
			url: jQuery(this).attr('href'),
			type: "DELETE",
			data: jQuery(this).data('id')
    	});
	});
</script>


@stop
