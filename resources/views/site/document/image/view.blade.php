@extends('site.layout.default-new')

@section('title')
	{{ $page->title }} - {{ $item->title }}
@stop
@section('meta_tags')
    @if($item)
        <meta name='description' itemprop='description' content='{!!strip_tags($item->content)!!}' />
        <meta property='article:published_time' content='{{$item->created_at}}' />
        <meta property='article:section' content='event' />

        <meta property="og:description" content="{!!strip_tags($item->content)!!}" />
        <meta property="og:title" content="{{$item->title}}" />
        <meta property="og:url" content="{{url()->current()}}" />
        <meta property="og:type" content="article" />
        <meta property="og:locale" content="en-us" />
        <meta property="og:locale:alternate" content="en-us" />
        <meta property="og:site_name" content="{{env('SITE_URL', 'kythera-family.net')}}" />
        <meta property="og:image" content="{{$item->image}}" />
        <meta property="og:image:url" content="{{$item->image}}" />
        <meta property="og:image:size" content="300" />

    @endif
@endsection


@section('content')
      <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    @include('partials.front.left-front-menu')

                    @include('site.document.image.blocks.view.content')
                </div>
            </div>
        </div>
    </main>
@stop