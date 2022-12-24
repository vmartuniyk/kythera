@extends('site.layout.default')

@section('title')
    Error
@stop

@section('content')
<div class="container">
    <h1>Something has gone wrong.</h1>
    <p>Incident reference #{!!$ref!!}</p>
    {!! $message !!}
</div>
@stop