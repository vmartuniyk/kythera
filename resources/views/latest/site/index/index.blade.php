@extends('site.layout.default')

@section('title')
@parent
 :: {{ Lang::get('site.index') }}
@stop

@section('content')

{!! Shop::greeting() !!}

{{--
    {!! Helpers::doMessage() !!}
    {!! Notifier\EmailNotifier::notify() !!}
    {!! EmailNotifier::notify() !!}
--}}

    <center>
        <p>You can login with your original credentials.</p>
        <a href="/en/admin" class="btn btn-black">{!!trans('locale.goto-admin')!!}</a>
    </center>
@stop