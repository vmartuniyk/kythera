@extends('site.layout.default')

@section('title')
    Redirection
@stop

@section('content')
<div class="container">
    <h1>Redirection request</h1>
    <p>Incident reference #{!!$ref!!}</p>
    <p>
    You arrived here because the imported documents can contain links referencing the old website.
    <pre>
    <a href="{!! urldecode(URL::previous()) !!}">{!! urldecode(URL::previous()) !!}</a>
    </pre>

    {{--
    However our guess is that the new page would be:
    <pre>
    {!!$destination!!}
    </pre>
    --}}

    </p>
</div>
@stop