@extends('site.layout.default-new')

@section('title')
    Entry
@stop

@section('style')
@stop

@section('content')
<div class="container">
    <div class="head">
      <h1 class="pull-left">Entry</h1>
      <div class="crumb pull-right">Home > <span>Entry</span></div>
        <br class="clear"/>
    </div>
    <hr class="thin"/>

    <div class="content entry">
    	@if(Session::has('global'))<p class="bg-info">{!! Session::get('global') !!}</p>@endif
    </div>
</div><!-- container -->
@stop