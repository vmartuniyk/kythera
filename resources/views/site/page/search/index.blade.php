@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
.content.search .form-group {margin:0}
.content.search ul.entries {display: block;list-style: none;padding:0}
.content.search ul.entries .highlight {color:red;xfont-weight:700}
.content.search ul.related {display: block;list-style: none;padding:0}
.content.search h3 {font-size: 20px;color:#707070}
.content.search ul.entries li em {color:black;font-weight:bold}
.content.search ul.entries li .author {font: italic 12px/18px arial;color:#707070}
@stop

@section('content')
<div class="container">

    <div class="head">
        <h1 class="pull-left">{{ $page->title }}</h1>
        <div class="crumb pull-right">
        	{!! xhtml::crumbs(Router::getSelected(), ' &gt; ', false) !!}
        </div>
    </div>
    <br class="clear"/>

    <hr class="thin"/>
    <div class="content search">
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

		{!! Form::open(array('action'=>'ElasticSearchPageController@getIndex', 'method'=>'get', 'id'=>'search', 'class'=>'form-horizontal', 'autocomplete'=>'on')) !!}
		<div class="form-group">
			{!! Form::label('q', 'Enter keywords', array('for'=>'query')) !!}
			{!! Form::text('q', Session::get('query'), array('id'=>'q', 'placeholder'=>'Enter keywords...', 'class'=>'form-control')) !!}

			{!! Form::label('c', 'Category', array('for'=>'c')) !!}
			<select class="form-control" name="c" id="c">
			{{--
				<option value=0>-</option>
				@foreach($categories as $category)
				<option value="{!! $category->controller_id !!}" {!! $category->controller_id == Session::get('category_filter', 0) ? 'selected="selected"' : '' !!}>{!! $category->title !!}</option>
				@endforeach
			--}}
			{!! xmenu::categories($categories, Session::get('category_filter', 0), 0) !!}
			</select>

			{!! Form::label('a', 'Author', array('for'=>'a')) !!}
			<select class="form-control" name="a" id="a">
				<option value=0>-</option>
				@foreach($authors as $author)
				<option value="{!! $author->id !!}" {!! $author->id == Session::get('author_filter', 0) ? 'selected="selected"' : '' !!}>{!! $author->lastname !!}, {!! $author->firstname !!}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group form-actions">
			{!! Form::button(trans('locale.button.search'), array('type'=>'submit', 'class'=>'btn btn-default btn-black')) !!}
		</div>
		{!! Form::close() !!}


		<div class="txtdoc-list">
		<hr class="blue"/>
        @if (isset($items) && count($items))
            <div class="clearfix">
            	<span class="pull-right">{!!$pages->render()!!}</span>
            </div>

	        <div class="txtdoc-filter clearfix">
	            <span class="pull-left">{!!trans('locale.filter.showing', array(
	                'start'=>$pages->firstItem(),
	                'end'=>$pages->firstItem()+$pages->count()-1,
	                'total'=>$pages->total()
	                ))!!}</span>
	            <div class="pull-right">
	                <form method="get" action="{!! URL::full() !!}">
	                    {!!trans('locale.filter.sortedby')!!}:
	                    {!!Form::select('po', $paginate_orders, Session::get('paginate_order'), array('class'=>'filter auto-submit'))!!}
	                </form>
	            </div>

                <br/>
                <br/>
                <div class="line"></div>
            </div>

	        <ul class="entries">
	        	@foreach($items as $i=>$item)
	        	<li>
	        		<div>
	        			{!!$i+$pages->firstItem()!!}.
	        			@if (Config::get('app.debug'))
	        			{!!$item['id']!!}:
	        			@endif
		        		{!!$item['crumbs']!!} &gt; <a href="{!!$item['url2']!!}" >{!!$item['title']!!}</a>
		        		<br>
		        		<a href="{!!$item['url2']!!}">{!!urldecode($item['url2'])!!}</a>
		        		<br>
		        		{!!$item['value']!!}
		        		{{--<i class="c">({!!$item['label']!!})</i>--}}
		        		<br>
		        		<span class="author">Submitted by {!!$item['author']!!} on {!!$item['year']!!}</span>
		        		<br>

	        		</div>
	        		@if ($i!=count($items)-1)
	        		<hr class="thin"/>
	        		@endif
	        	</li>
	        	@endforeach
	        </ul>

	        {{--
	        @if (isset($related) && count($related))
	        <h3>Related searches for {{ Session::get('query') }}</h3>
	        <ul class="related">
	        	@foreach($related as $key=>$entities)
	        	<li><a href="/en/esearch?r=&q={!!$key!!}">{{ $key }}</a></li>
	        	@endforeach
	        </ul>
	        @endif
	        --}}

	        <span class="pull-right">{!!$pages->render()!!}</span>
	    @else
	        <p>There are currently no entries in this section.</p>
	    @endif
	    </div>


    </div>
</div>
@stop

@section('javascript')
@stop
