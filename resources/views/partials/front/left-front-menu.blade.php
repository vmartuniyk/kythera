<aside class="inner-page__aside aside" id="aside">
    <nav class="aside__nav" id="aside-nav" data-spollers data-one-spoller>
        <div class="aside__item">
            <button type="button" class="aside__title {{ (request()->segment(2) == 'people') ? '_spoller-active' : '' }}" data-spoller>
                <span>Kytherian People</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/people/names" class="aside__link" title="Names">Names</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/people/nicknames" class="aside__link" title="Nicknames">Nicknames</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/people/life-stories" class="aside__link" title="Life Stories">Life Stories</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/people/notable-kytherians" class="aside__link" title="Notable Kytherians">Notable Kytherians</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/people/surnames-book" class="aside__link" title="Surnames Book">Surnames Book</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/people/obituaries" class="aside__link" title="Obituaries">Obituaries</a>
                </li>

            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title {{ (request()->segment(2) == 'gravestones') ? '_spoller-active' : '' }}" data-spoller>
                <span>Gravestones</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/gravestones/gravestones" class="aside__link" title="Gravestones">Gravestones</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/australian-armed-forces" class="aside__link" title="Australian Armed Forces">Australian Armed Forces</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/ag-anastasia" class="aside__link" title="Ag Anastasia">Ag Anastasia</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/agios-theothoros" class="aside__link" title="Agios Theothoros">Agios Theothoros</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/aroniathika" class="aside__link" title="Aroniathika">Aroniathika</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/australia" class="aside__link" title="Australia">Australia</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/drymonas" class="aside__link" title="Drymonas">Drymonas</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/friligianika" class="aside__link" title="Friligianika">Friligianika</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/gerakari" class="aside__link" title="Gerakari">Gerakari</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/gouthianika" class="aside__link" title="Gouthianika">Gouthianika</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/hora" class="aside__link" title="Hora">Hora</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/kaspali" class="aside__link" title="Kaspali">Kaspali</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/karavas" class="aside__link" title="Karavas">Karavas</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/keramoto" class="aside__link" title="Keramoto">Keramoto</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/logothetianika" class="aside__link" title="Logothetianika">Logothetianika</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/livathi" class="aside__link" title="Livathi">Livathi</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/mitata" class="aside__link" title="Mitata">Mitata</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/panagia-despina" class="aside__link" title="Panagia Despina">Panagia Despina</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/pitsinianika" class="aside__link" title="Pitsinianika">Pitsinianika</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/potamos" class="aside__link" title="Potamos ">Potamos </a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/tryfillianika" class="aside__link" title="Tryfillianika">Tryfillianika</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/gravestones/usa" class="aside__link" title="USA">USA</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <a class="aside__title aside_single {{ (request()->segment(2) == 'villages-towns') ? 'current' : '' }} " href="/en/villages-towns">
                <span>Villages</span>
            </a>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title {{ (request()->segment(2) == 'history') ? '_spoller-active' : '' }}" data-spoller>
                <span>Island History</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/history/archaeology" class="aside__link" title="Archaeology">Archaeology</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/archive-research" class="aside__link" title="Archive/Research">Archive/Research</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/artefacts" class="aside__link" title="Artefacts">Artefacts</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/documents" class="aside__link" title="Documents">Documents</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/general-history" class="aside__link" title="General History">General History</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/myths-and-legends" class="aside__link" title="Myths and Legends">Myths and Legends</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/old-letters" class="aside__link" title="Old Letters">Old Letters</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/oral-history" class="aside__link" title="Oral History">Oral History</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/photography" class="aside__link" title="Photography">Photography</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/history/vintage-maps" class="aside__link" title="Vintage Maps">Vintage Maps</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title {{ (request()->segment(2) == 'culture') ? '_spoller-active' : '' }}" data-spoller>
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
            <button type="button" class="aside__title {{ (request()->segment(2) == 'natural-history-museum') ? '_spoller-active' : '' }} " data-spoller>
                <span>Natural History Museum</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/birds" class="aside__link" title="Birds ">Birds </a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/fish" class="aside__link" title="Fish">Fish</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/flowers" class="aside__link" title="Flowers">Flowers</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/fossils" class="aside__link" title="Fossils">Fossils</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/insects-and-kin" class="aside__link" title="Insects and Kin">Insects and Kin</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/mammals" class="aside__link" title="Mammals">Mammals</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/reptiles-amphibians" class="aside__link" title="Reptiles &amp; Amphibians">Reptiles &amp; Amphibians</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/rocks" class="aside__link" title="Rocks">Rocks</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/seashells-miscellany" class="aside__link" title="Seashells - Miscellany">Seashells - Miscellany</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/seashells-gastropods" class="aside__link" title="Seashells - Gastropods">Seashells - Gastropods</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/seashells-bivalves" class="aside__link" title="Seashells - Bivalves">Seashells - Bivalves</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/natural-history-museum/urchins-crabs" class="aside__link" title="Urchins &amp; Crabs">Urchins &amp; Crabs</a>
                </li>
            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title {{ (request()->segment(2) == 'academic-research') ? '_spoller-active' : '' }}" data-spoller>
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
            <button type="button" class="aside__title {{ (request()->segment(2) == 'tourist-information') ? '_spoller-active' : '' }}" data-spoller>
                <span>Tourist Information</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item">
                    <a href="/en/tourist-information/addresses-numbers" class="aside__link" title="Addresses &amp; Numbers">Addresses &amp; Numbers</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/tourist-information/internet-wireless" class="aside__link" title="Internet &amp; Wireless">Internet &amp; Wireless</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/tourist-information/sightseeing" class="aside__link" title="Sightseeing">Sightseeing</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/tourist-information/where-to-eat" class="aside__link" title="Where to eat">Where to eat</a>
                </li>
                <li class="aside__list-item">
                    <a href="/en/tourist-information/where-to-stay" class="aside__link" title="Where to stay">Where to stay</a>
                </li>

            </ul>
        </div>
        <div class="aside__item">
            <button type="button" class="aside__title {{ in_array(request()->segment(2),['photos','audiovideo'])  ? '_spoller-active' : '' }}" data-spoller>
                <span>Photos &amp; Videos</span>
                <svg xmlns="http://www.w3.org/2000/svg" stroke="currentColor" width="17.386" height="9.799" viewBox="0 0 17.386 9.799">
                    <path data-name="Path 212" d="M888,80l-8,7.691L872,80" transform="translate(-871.307 -79.279)" fill="none" stroke-miterlimit="10" stroke-width="2" />
                </svg>
            </button>
            <ul class="aside__list">
                <li class="aside__list-item" >
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
                <li class="aside__list-item" >
                    <a href="/en/audiovideo/vintage-films" class="aside__link {{ (request()->segment(3) == 'vintage-films') ? 'aside__title current' : '' }}">Vintage Films</a>
                </li>

            </ul>
        </div>
        <div class="aside__item">
            <a class="aside__title aside_single {{ (request()->segment(2) == 'message-board') ? 'current' : '' }}" href="/en/message-board">
                <span>Message Board</span>
            </a>
        </div>
        <div class="aside__item">
            <a class="aside__title aside_single {{ (request()->segment(2) == 'guestbook') ? 'current' : '' }}" href="/en/guestbook">
                <span>Guestbook</span>
            </a>
        </div>

        <div class="aside__item">
            <a class="aside__title aside_single {{ (request()->segment(2) == 'newsletter-archive') ? 'current' : '' }}" href="/en/newsletter-archive">
                <span>Newsletter Archive</span>
            </a>
        </div>
    </nav>
</aside>
