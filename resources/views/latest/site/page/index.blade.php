@extends('site.layout.default')

@section('title')
    {{ $page->title }}
@stop

@section('content')
    <h1>{{ $page->title }}</h1>
    {{ $page->content }}
@stop