@extends('site.layout.default-new')

@section('title')
    404
@stop

@section('content')
    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    <div class="container" style="padding: 100px">
                        <h1>Page not found!</h1>
                        <p>
                            The requested page could not be found. Apologies for the inconvenience.
                            <br/><samp class="blue">{!! rawurldecode(URL::current()) !!}</samp>

                            <br/>
                            Please <a href="/en/contact">contact</a> us if you think something is missing or goto the <a href="/en/helpfaq">help/FAQ</a> section.

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
{{--<div class="container">--}}
{{--    <h1>Page not found!</h1>--}}
{{--    <p>--}}
{{--    The requested page could not be found. Apologies for the inconvenience.--}}
{{--    <br/><samp class="blue">{!! rawurldecode(URL::current()) !!}</samp>--}}
{{--    <br/>--}}
{{--    <br/>--}}

{{--    {!! Form::open(array('action'=>'ElasticSearchPageController@getIndex', 'method'=>'GET', 'id'=>'search', 'class'=>'form-horizontal', 'autocomplete'=>'on')) !!}--}}
{{--    {!! Form::label('q', 'You could try to search for the information you were looking for:', array('for'=>'query')) !!}--}}
{{--    {!! Form::text('q', $q, array('id'=>'q', 'placeholder'=>'Enter keywords...', 'class'=>'form-control')) !!}--}}
{{--    {!! Form::hidden('source', '404') !!}--}}
{{--    {!! Form::hidden('url', rawurldecode(URL::current())) !!}--}}
{{--    <br/>--}}
{{--    Please <a href="/en/contact">contact</a> us if you think something is missing or goto the <a href="/en/helpfaq">help/FAQ</a> section.--}}

{{--    <hr class="thin"/>--}}
{{--    {!! Form::button(trans('locale.button.search'), array('type'=>'submit', 'class'=>'btn btn-default btn-black')) !!}--}}
{{--    {!! Form::close() !!}--}}
{{--    </p>--}}
{{--</div>--}}
@stop