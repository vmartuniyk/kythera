<div class="js-cookie-consent cookie-consent sticky">

    <span class="cookie-consent__message">
        Our site uses cookies to help you find what you are looking for.
        For more information, visit our <a href="{{ LaravelLocalization::getLocalizedURL('en', 'terms-of-use') }}" target="_blank">Terms of Use page</a>
    </span>

    <button class="js-cookie-consent-agree cookie-consent__agree">
        Allow cookies
    </button>

</div>

@section('stylesheet')
    <style>
    .cookie-consent {
        font-size: 0.9em;
        padding: 1em;
        background: #fff2e0;
        text-align: center;
    }

    .cookie-consent__message {
        display: inline-block;
        color: #d98e00;
    }

    .cookie-consent__message p {
        margin: 0;
    }

    .cookie-consent__agree {
        font-weight: bold;
        margin: 0 1em;
        padding: .5em 1em;
        color: #fff2e0;
        background-color: #d98e00;
        border: 0;
        border-radius: 3px;
        box-shadow: 0 2px 5px rgba(217, 142, 0, 0.15);
    }

    .cookie-consent__agree:hover {
        background-color: #734d00;
    }

    .cookie-consent__agree:active {
        top: 1px;
    }

    .sticky {
      position: -webkit-sticky; /* Safari */
      position: sticky;
      top: 0;
      z-index: 99;
    }
    </style>
@endsection
