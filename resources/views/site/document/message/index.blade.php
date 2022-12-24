@extends('site.layout.default-new')

@section('title')
    {{ $page->title }}
@stop

@section('style')
.messages .txtdoc p.content {
    color: #000;
    font: 14px/19px arial;
}
.txtdoc {
    border-bottom: 1px solid #dfdfdf;
    padding: 10px 0;
}

/*
*/
/*
.guestbook h3 {
    color: #000;
    font: 700 14px/14px arial;
    text-transform: uppercase;
}
*/
.form-group {
	padding-left:15px
}
span.date {
    font: 12px/12px arial;
}
#recaptcha_widget_divx, #recaptcha_table {
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    xcolor: #555;
    display: block;
    xtransition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 440px;
}
#recaptcha_response_field {
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc !important;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555;
    display: block;
    font-size: 14px;
    height: 34px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    width: 100%;
}
#recaptcha_response_field:focus {
	border-color:#66afe9;
	box-shadow: 0 1px 1px rgba(0,0,0,0.075) inset, 0 0 8px rgba(102,175,233,0.6);
	outline:0 none;
}
#recaptcha_response_field-error {
	color: red !important;
	margin-bottom:10px !important;margin-top:10px !important;
}
#recaptcha_image {
	height:59px !important;
	width:302px !important;
}
@stop

@section('content')
    <main class="page">
        <div class="inner-page">
            <div class="inner-page__container">
                <div class="inner-page__wrap">
                    @include('partials.front.left-front-menu')

                    <div class="inner-page__content content-inner text-first-screen">
                        <div class="content-inner__wrap">
                            @include('site.document.message.blocks.index.head')

                            <section class="content-inner__articles inner-articles">
                                @include('site.document.message.blocks.index.content')
                            </section>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop

