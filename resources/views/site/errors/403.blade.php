@extends('site.layout.default')

@section('title')
    Permission Denied
@stop

@section('content')
<div class="container">
    <h1>Access Denied!</h1>
    <p>
    You do not have permission to access this resource.
    <br/><samp class="blue">{!! rawurldecode(URL::current()) !!}</samp>
    </p>
</div>
@stop
