@extends('site.layout.default')

@section('title')
    404
@stop

@section('jumbotron')
    <div class="jumbotron">
      <div class="container">
        <h1>404</h1>
      </div>
    </div>
@stop

@section('content')
    404
    <br>
    {!! App::getLocale(); !!} <br>
    {!! Request::path(); !!} <br>
    {!! Request::segment(1); !!} <br>
    {!! Request::segment(2); !!} <br>
    
@stop