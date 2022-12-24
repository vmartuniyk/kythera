@extends('site.layout.default')

@section('title')
@parent
 :: {{ Lang::get('locale.about') }}
@stop

@section('content')
    <p>About</p>
@stop
