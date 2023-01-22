<aside class="inner-page__aside aside profile-side" id="aside">
    <div class="user">
        <div class="user__photo">
            <img src="{{ URL::asset('assets/img/icons/user-big.svg') }}" alt="">
        </div>
        <div class="user__info">
            <div class="user__name">
                {{ Auth::user()->full_name }}
                @if (Auth::user()->isAdmin())
                    (administrator)
                @endif
            </div>
            <div class="user__bottom">
                <div class="user__groups">Member of <span>13</span> Groups</div>
                <div class="user__active">
                    Active Since
                    <time datetime="{{ Auth::user()->created_at->format('Y-m-d') }}">
                        {{ Auth::user()->created_at->format('Y/m/d') }}
                    </time>
                </div>
            </div>
        </div>
    </div>
    <nav class="aside__nav" id="aside-nav">
        <ul class="aside__wrap">
            <li class="aside__item">
                <a href="/your-personal-page/categories" class="aside__title">
                    Entries
                    <span></span>
                </a>
            </li>
            <li class="aside__item">
                <a href="/your-personal-page/comments" class="aside__title active">
                    Comments
                    <span></span>
                </a>
            </li>
            <li class="aside__item">
                <a href="#" class="aside__title">
                    Family Trees
                    <span></span>
                </a>
            </li>
            <li class="aside__item">
                <a href="#" class="aside__title active">
                    Groups
                    <span></span>
                </a>
            </li>
            <li class="aside__item">
                <a href="/entry/create" class="aside__title">
                    Upload New Entry
                    <span></span>
                </a>
            </li>
            <li class="aside__item">
                <a href="/your-personal-page/edit" class="aside__title">
                    Edit Account
                    <span></span>
                </a>
            </li>
            <li class="aside__item">
                <a href="{{ action('UsersController@getLogout' )}}" class="aside__title">
                    Logout
                    <span></span>
                </a>
            </li>
        </ul>
    </nav>
</aside>