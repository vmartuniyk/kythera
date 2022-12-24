@extends('site.layout.default')

@section('title')
INFO
@stop

@section('content')
    @if (isset($crumbs))
    <span class="pull-right">{!! $crumbs !!}</span>
    @endif
    
    <h1>{!!$title!!}</h1>
    
    <br>URI: {!!Request::path()!!}
    <br>ROUTE: <pre>{!!print_r(Route::getSelected(),1)!!}</pre>
    
    @if (isset($parameters))
    PARAMETERS:
    <pre>{!! print_r($parameters,1) !!}</pre>
    @endif
    
<select name="select_projects" id="select_projects">
    <option value="">project.xml</option>
    <optgroup label="client1">
        <option value="">project2.xml</option>
    </optgroup>
    <optgroup label="client2">
        <option value="">project5.xml</option>
        <option value="">project6.xml</option>
        <optgroup label="client2_a">
            <option value="" style="margin-left:23px;">project7.xml</option>
            <option value="" style="margin-left:23px;">project8.xml</option>
        </optgroup>
        <option value="">project3.xml</option>
        <option value="">project4.xml</option>
   </optgroup>
   <option value="">project0.xml</option>
   <option value="">project1.xml</option>
</select>

{{--
    {!! Helpers::doMessage() !!}
    {!! Notifier\EmailNotifier::notify() !!}
    {!! EmailNotifier::notify() !!}
--}}
@stop