@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('style')
	ul.a {list-style:none;padding:0}
	ul.a li {float:left;margin:2px}
@stop

@section('content')
    <div class="container">
        @if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif
        <div class="head">
        	<h1 class="pull-left">{{ $page->title }}</h1>
        	<div class="crumb pull-right">
	            {{--
	        	@if (Auth::check())
	        	<a href="{!! action('DocumentPersonController@create') !!}">Create new family tree</a>
	        	@else
	        	<a href="{!!URL::previous()!!}">Back to family tree</a>
	        	@endif
	            --}}
	            <a class="blue" href="{!! action('DocumentPersonController@create') !!}" title="Submit an entry here!"> Submit an entry here!</a>
        	</div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
            <div class="col-md-4">
	            <ul class="clearfix a">
	            @foreach ($alphabet as $i=>$letter)
                    @if (Session::has('family.selLet') && (Session::get('family.selLet')==$i))
                        <li><a href="?selLet={!!$i!!}" style="font-weight:700">{!!$letter!!}</a>
                    @else
	            	  <li><a href="?selLet={!!$i!!}">{!!$letter!!}</a>
                    @endif
	            @endforeach
	            </ul>
	            @if (is_object($names))
	            	<b>{!!$names->families->name!!} ({!!$names->families->count!!})</b>
	            	<ul class="n">
	            	@foreach ($names->members as $i=>$name)
	            		<li><a href="{!!route('site.page.family.trees.entry', $name->did)!!}">{!!$name->fullname!!} <i>{!!\Kythera\Models\Person::formatPersonYearDates($name)!!}</i></a></li>
	            	@endforeach
	            	</ul>
	            @else
	            	<ul class="n">
	            	@foreach ($names as $i=>$name)
	            		<li><a href="?selLet={!!Input::get('selLet',0)!!}&selName={!!$name->persons_id!!}">{!!$name->name!!} ({!!$name->count!!})</a>
	            	@endforeach
	            	</ul>
	            @endif

				<ul class="">
					@foreach ($trees as $i=>$tree)
					<li><a href="{!!route('site.page.family.trees.entry', $tree->entry_id)!!}">{!!$tree->entry_id!!} {!!$tree->n!!}</a>
                    @endforeach
				</ul>

            </div>
            <div class="col-md-8">
                <p>
                {!! $page->content !!}
                </p>
            </div>
        </div>
    </div>
@stop
