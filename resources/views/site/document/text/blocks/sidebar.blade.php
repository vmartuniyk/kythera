{{-- <hr class="line gray clear toolbar">

@if ($item = Session::get('selected.item', null))
    <div class="toolbar-view">
        {!! xmenu::entry_edit($item->user_id, $item) !!}
    </div>
@else
    <div class="toolbar-view">
        {!! xmenu::entry_edit(null, null) !!}
    </div>
@endif

<!-- sidebar/posts -->
@include('site.document.text.blocks.sidebar.posts')
<!-- /sidebar/posts -->

<!-- sidebar/media -->
@include('site.document.text.blocks.sidebar.media')
<!-- /sidebar/media -->

<!-- sidebar/tourist -->
@include('site.document.text.blocks.sidebar.tourist')
<!-- /sidebar/tourist -->

<!-- sidebar/board -->
@include('site.document.text.blocks.sidebar.message')
<!-- /sidebar/board --> --}}
<aside class="inner-page__aside aside" id="aside">
    <nav class="aside__nav" id="aside-nav" data-spollers data-one-spoller>
        <div class="aside__item">
            <button type="button" class="aside__title _spoller-active" data-spoller>
                <span>Kytherian People</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Kytherian People</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Family Trees</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Villages</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Island History</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Greek Mythology</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Culture</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Arts & Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Academic Research</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Family Names</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Kytherian People</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Family Trees</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Villages</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Island History</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Greek Mythology</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Culture</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Arts & Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Academic Research</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Villages</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Kytherian People</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Family Trees</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Villages</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Island History</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Greek Mythology</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Culture</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Arts & Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Academic Research</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Island History</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Kytherian People</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Family Trees</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Villages</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Island History</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Greek Mythology</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Culture</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Arts & Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Academic Research</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Greek Mythology</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Kytherian People</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Family Trees</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Villages</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Island History</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Greek Mythology</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Culture</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Arts & Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Academic Research</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Culture</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/culture/associations"  class="aside__link" title="Associations">Associations</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/architecture"  class="aside__link" title="Architecture">Architecture</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/blogs"  class="aside__link" title="Blogs">Blogs</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/bibliography"  class="aside__link" title="Bibliography">Bibliography</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/current-affairs"  class="aside__link" title="Current Affairs">Current Affairs</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/home-remedies"  class="aside__link" title="Home Remedies">Home Remedies</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/kytherian-artscraft"  class="aside__link" title="Kytherian Arts/Craft">Kytherian Arts/Craft</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/kyth-business-guide"  class="aside__link" title="Kyth. Business Guide">Kyth. Business Guide</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/kytherian-identity"  class="aside__link" title="Kytherian Identity">Kytherian Identity</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/museums-galleries"  class="aside__link" title="Museums &amp; Galleries">Museums &amp; Galleries</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/music-songs"  class="aside__link" title="Music-Songs">Music-Songs</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/nature"  class="aside__link" title="Nature">Nature</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/religion"  class="aside__link" title="Religion">Religion</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/culture/sayings-and-proverbs"  class="aside__link" title="Sayings and Proverbs">Sayings and Proverbs</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Arts & Music</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Kytherian People</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Family Trees</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Villages</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Island History</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Greek Mythology</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Culture</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Arts & Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="#" class="aside__link">Academic Research</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Academic Research</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/academic-research/archaeology" class="aside__link">Archaeology</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/demography" class="aside__link">Demography</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/diasporamigration" class="aside__link">Diaspora/Migration</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/education" class="aside__link">Education</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/environmentecology" class="aside__link">Environment/Ecology</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/ethnology-anthropology-folklore" class="aside__link">Ethnology/Anthropology/Folklore</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/history" class="aside__link">History</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/religionchurch" class="aside__link">Religion/Church</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/sciences" class="aside__link">Sciences</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/academic-research/society-of-kytherian-studies" class="aside__link">Society of Kytherian Studies</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title" data-spoller>
                <span>Photos &amp; Videos</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/photos/architecture" class="aside__link">Architecture</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspore-cafes-shops-cinemas" class="aside__link">Cafes, Shops & Cinemas</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspore-churches-icons" class="aside__link">Churches & Icons</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspora-churches-icons" class="aside__link">Diaspora Churches & Icons</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspora-social-life" class="aside__link">Diaspora Social Life</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspora-weddings-and-proxenia" class="aside__link">Diaspora Weddings and Proxenia</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspora-vintage-portraits-people" class="aside__link">Diaspora Vintage Portraits/ People</a>   
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/great-walls" class="aside__link">Great Walls</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/island-churches-icons" class="aside__link">Island Churches &amp; Icons</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/island-social-life" class="aside__link">Island Social Life</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/island-weddings-and-proxenia" class="aside__link">Island Weddings and Proxenia</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspore-kytherian-art" class="aside__link">Kytherian Art</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/miscellaneous" class="aside__link">Miscellaneous</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/modern-landscapes" class="aside__link">Modern Landscapes</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/modern-portraits" class="aside__link">Modern Portraits</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/nature" class="aside__link">Nature</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/school-photos" class="aside__link">School Photos</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/signs-statues" class="aside__link">Signs &amp; Statues</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspore-sporting-life" class="aside__link">Sporting Life</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/vintage-landscapes" class="aside__link">Vintage Landscapes</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/vintage-portraits-people" class="aside__link">Vintage Portraits/ People</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/working-life" class="aside__link">Working Life</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/photos/diaspore-working-life" class="aside__link">Diaspora Working Life</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/audiovideo/diaspora-interviews" class="aside__link">Diaspora Interviews</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/audiovideo/island-interviews" class="aside__link">Island Interviews</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/audiovideo/kytherian-music" class="aside__link">Kytherian Music</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/audiovideo/sounds-of-nature" class="aside__link">Sounds of Nature</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/audiovideo/vintage-films" class="aside__link">Vintage Films</a>
                </li>

            </ul>
        </div>
    </nav>
</aside>
            