@extends('site.layout.default')

@section('title')
@parent
 :: {{ Lang::get('site.people.names.index') }} :: {{ $item->lastname }}
@stop

@section('style')
@stop

@section('content')
    <h1>People > Names > {{ $item->lastname }}</h1>
    <br />

    <br />id: {!! $item->id !!} 
    <br />f: {!! $item->firstname !!} 
    <br />m: {!! $item->middlename !!} 
    <br />l: {!! $item->lastname !!} 
    <br />s: /{!! $item->slug() !!} 
    
@stop