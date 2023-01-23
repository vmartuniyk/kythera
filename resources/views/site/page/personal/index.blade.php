@extends('site.layout.default-new')

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
	<main class="page">
		<div class="inner-page">
			<div class="inner-page__container">
				<div class="inner-page__wrap">

					@include('partials.admin.left-menu')
					<div class="inner-page__content content-inner profile-page">
						<div class="content-inner__wrap">
							<section class="profile-content">
								<div class="profile-content__top profile-top">
									<h1 class="profile-top__title">Welcome to Your Profile</h1>
									<p class="profile-top__text">
										Your profile page allows you to see entries that you’ve pinned, view Group activity and engage with the Kytherian diaspora community. View your activity below.
									</p>
									<a href="#" class="profile-top__upload-btn btn btn-one-color">
										Upload New Entry
										<svg class="icon-arrow-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
											<g data-name="Group 12" transform="translate(-833.866 0.693)">
												<path data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
												<line data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
											</g>
										</svg>
									</a>
								</div>
							</section>
{{--							<div class="row">--}}
{{--								<div class="col-md-4">--}}
{{--									<h2>Your entries</h2>--}}
{{--									<p class="stats">{!!$cat_stat!!}</p>--}}
{{--									<ul>--}}
{{--										@foreach($categories as $category)--}}
{{--											<li>--}}
{{--												<a href="{!!route('site.page.your.personal.page.category', $category->document_type_id)!!}#list">--}}
{{--													@if (Config::get('app.debug'))--}}
{{--														{!!$category->document_type_id!!}:--}}
{{--													@endif--}}
{{--													{{ $category->cat }} ({!!$category->n!!})--}}
{{--												</a>--}}
{{--											</li>--}}
{{--										@endforeach--}}
{{--									</ul>--}}
{{--								</div>--}}

{{--								<div class="col-md-4">--}}
{{--									<h2>Your comments</h2>--}}
{{--									<p class="stats">{!!$com_stat!!}</p>--}}
{{--									<ul>--}}
{{--										@foreach($comments as $comment)--}}
{{--											@if(isset($comment->category))--}}
{{--												<li>--}}
{{--													<a href="{!!route('site.page.your.personal.page.comment', $comment->document_type_id)!!}#list">--}}
{{--														@if (Config::get('app.debug'))--}}
{{--															{!!$comment->document_type_id!!}:--}}
{{--														@endif--}}
{{--														{{ $comment->category->title }} ({{ $comment->n }})--}}
{{--													</a>--}}
{{--												</li>--}}
{{--											@endif--}}
{{--										@endforeach--}}
{{--									</ul>--}}
{{--								</div>--}}

{{--								<div class="col-md-4">--}}
{{--									<h2>Your family trees</h2>--}}
{{--									<p class="stats">{!!$person_stat!!}</p>--}}
{{--									<ul>--}}
{{--										@foreach($persons as $person)--}}
{{--											<li>--}}
{{--												<a href="{!!action('DocumentPersonController@edit', $person->entry_id)!!}">--}}
{{--													@if (Config::get('app.debug'))--}}
{{--														{!!$person->id!!}:--}}
{{--													@endif--}}
{{--													{{ $person->displayname }}--}}
{{--												</a>--}}

{{--												<span class="stats">{!!$person->invited ? '(Invited)' : ''!!}</span>--}}
{{--											</li>--}}
{{--										@endforeach--}}
{{--									</ul>--}}
{{--								</div>--}}

{{--							</div>--}}
{{--							@if (isset($items) && count($items))--}}
{{--								<hr class="blue"/>--}}

{{--								<a name="list">&nbsp;</a>--}}
{{--								<h2>--}}
{{--									@if (Config::get('app.debug'))--}}
{{--										{!!$page->document_type_id!!}:--}}
{{--									@endif--}}
{{--									{!!$page->title!!}--}}
{{--								</h2>--}}
{{--								<ul class="entries">--}}

{{--									@if($list == 'category')--}}
{{--										@foreach($items as $item)--}}
{{--											<li>--}}
{{--												<div class="clearfix" style="position:relative;">--}}
{{--													<div class="pull-left category">--}}
{{--														<a href="{!!Router::getItemUrl($item)!!}">--}}
{{--															@if (Config::get('app.debug'))--}}
{{--																{!!$item->id!!}:--}}
{{--															@endif--}}
{{--															{{ $item->title }}--}}
{{--														</a>--}}
{{--														<br/>--}}
{{--														<span class="date">{!!$item->created_at->format('d.m.Y')!!}</span>--}}
{{--													</div>--}}


{{--													<div class="toolbar">--}}
{{--														<a class="btn btn-default btn-xs" href="{!!action('EntryController@edit', $item->id)!!}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>--}}
{{--														@if ($item->enabled)--}}
{{--															<a class="btn btn-default btn-xs " href="{!!action('EntryController@enable', array($item->id, 0))!!}"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable</a>--}}
{{--														@else--}}
{{--															<a class="btn btn-default btn-xs " href="{!!action('EntryController@enable', array($item->id, 1))!!}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Enable</a>--}}
{{--														@endif--}}

{{--														@if (Auth::check() && Auth::user()->isAdmin())--}}
{{--															@if ($item->top_article)--}}
{{--																<a class="btn btn-default btn-xs" href="{!!action('EntryController@promote', array($item->id, 'degrade')) !!}"><span class="glyphicon glyphicon-star blue" aria-hidden="true"></span> Top article</a>--}}
{{--															@else--}}
{{--																<a class="btn btn-default btn-xs " href="{!!action('EntryController@promote', array($item->id, 'promote')) !!}"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Top article</a>--}}
{{--															@endif--}}

{{--														@endif--}}

{{--														--}}{{----}}
{{--                                                        @if (Config::get('app.entity.delete', false))--}}
{{--                                                        <a class="btn btn-danger delete" href="{!!action('EntryController@destroy', $item->id)!!}">&raquo; Delete</i></a>--}}
{{--                                                        @endif--}}
{{--                                                        --}}
{{--													</div>--}}


{{--												</div>--}}
{{--												<hr class="thin"/>--}}
{{--											</li>--}}
{{--										@endforeach--}}
{{--									@endif--}}


{{--									@if($list == 'comment')--}}
{{--										@foreach($items as $i=>$item)--}}
{{--											<li>--}}
{{--												<div class="clearfix" style="position:relative;">--}}
{{--													<div class="pull-left comment">--}}
{{--														<a href="{!!Router::getItemUrl($entries[$i])!!}">--}}
{{--															@if (Config::get('app.debug'))--}}
{{--																{!!$entries[$i]->id!!}:--}}
{{--															@endif--}}
{{--															{{ $entries[$i]->title }}--}}
{{--														</a>--}}
{{--														<br/>--}}
{{--														<span class="date">{!!$item->created_at->format('d.m.Y')!!}</span>--}}
{{--														<br/>--}}
{{--														@if (Config::get('app.debug'))--}}
{{--															{!!$item->id!!}:--}}
{{--														@endif--}}
{{--														{{ $item->comment }}--}}
{{--													</div>--}}

{{--													<div class="toolbar">--}}
{{--														<a class="btn btn-default btn-xs" href="{!!action('DocumentCommentController@edit', $item->id)!!}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>--}}
{{--														@if ($item->enabled)--}}
{{--															<a class="btn btn-default btn-xs " href="{!!action('DocumentCommentController@enable', array($item->id, 0))!!}"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable</a>--}}
{{--														@else--}}
{{--															<a class="btn btn-default btn-xs " href="{!!action('DocumentCommentController@enable', array($item->id, 1))!!}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Enable</a>--}}
{{--														@endif--}}


{{--														--}}{{----}}
{{--                                                        @if (Config::get('app.entity.delete', false))--}}
{{--                                                        <a class="btn btn-danger delete" href="{!!action('EntryController@destroy', $item->id)!!}">&raquo; Delete</i></a>--}}
{{--                                                        @endif--}}
{{--                                                        --}}
{{--													</div>--}}

{{--												</div>--}}
{{--												<hr class="thin"/>--}}
{{--											</li>--}}
{{--										@endforeach--}}
{{--									@endif--}}



{{--								</ul>--}}
{{--							@endif--}}
{{--							<section class="activity">--}}
{{--								<div class="activity__top">--}}
{{--									<h3 class="activity__title">Your Activity</h3>--}}
{{--									<div class="activity__sort-menu sort-menu-inner">--}}
{{--										<div class="sort-menu-inner__text">Sort By:</div>--}}
{{--										<div class="sort-menu-inner__select">--}}
{{--											<div class="sort-menu-inner__enter-field">--}}
{{--												<input type="text" id="input-select" value="Most Recent" readonly>--}}
{{--												<svg xmlns="http://www.w3.org/2000/svg" width="4.16" height="8.616" viewBox="0 0 4.16 8.616">--}}
{{--													<path data-name="Path 111" d="M1300.155,820.932l3.131,4-3.131,4" transform="translate(-1299.761 -820.623)" fill="none" stroke="#27646c" stroke-miterlimit="10" stroke-width="1" />--}}
{{--												</svg>--}}
{{--											</div>--}}
{{--											<ul class="sort-menu-inner__list">--}}
{{--												<li class="sort-menu-inner__item">The most popular</li>--}}
{{--												<li class="sort-menu-inner__item">Most Recent</li>--}}
{{--												<li class="sort-menu-inner__item">By date</li>--}}
{{--											</ul>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--								<ul class="activity__list">--}}
{{--									<li class="activity__item item-activity">--}}
{{--										<div class="item-activity__info">--}}
{{--											<a href="#" class="item-activity__link"></a>--}}
{{--											<div class="item-activity__image">--}}
{{--												<picture><source srcset="img/cards-img.webp" type="image/webp"><img src="img/cards-img.jpg?_v=1657459303074" alt=""></picture>--}}
{{--											</div>--}}
{{--											<div class="item-activity__wrap">--}}
{{--												<h5 class="item-activity__title">Example Entry Title</h5>--}}
{{--												<div class="item-activity__date">--}}
{{--													<time datetime="2017-03-24">24.03.2017</time> &bull;--}}
{{--													<span class="item-activity__autor">Stephen Tryfyllis</span>--}}
{{--												</div>--}}
{{--												<span class="item-activity__description">--}}
{{--														Photos, Churches, Icons--}}
{{--													</span>--}}
{{--											</div>--}}
{{--										</div>--}}
{{--										<div class="item-activity__publication">--}}
{{--											<div class="item-activity__time"><span>2</span> Hours Ago</div>--}}
{{--											<div class="item-activity__commented">Example User Commented On Your Entry</div>--}}
{{--										</div>--}}
{{--									</li>--}}

{{--								</ul>--}}

{{--							</section>--}}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="scroll-up-btn">
			<svg stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
				<g data-name="Group 12" transform="translate(-833.866 0.693)">
					<path data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke-miterlimit="10" stroke-width="2" />
					<line data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke-miterlimit="10" stroke-width="2" />
				</g>
			</svg>
		</div>
	</main>

	<!-- confirm delete dialog -->
{{--    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog">--}}
{{--            <div class="modal-content">--}}
{{--                {!! Form::open(array('method'=>'DELETE')) !!}--}}
{{--                    <div class="modal-header">--}}
{{--                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
{{--                        <h4 class="modal-title" id="myModalLabel">{title}</h4>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <p>{message}</p>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('locale.button.cancel') }}</button>--}}
{{--                        <button type="submit" class="btn btn-danger danger">{{ trans('locale.button.delete') }}</button>--}}
{{--                    </div>--}}
{{--                {!! Form::close() !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@stop


@section('js')
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
