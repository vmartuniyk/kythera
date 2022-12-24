@extends("admin.layout.default")

@section('style')
@stop

@section("content")
    <div class="import">
        <h1>{{ $title }}</h1>
        {!!$content!!}
    </div>
@stop

