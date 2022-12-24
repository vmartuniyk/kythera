<div class="container">
    <div class="row">
        <div class="col-md-4 col">
            <!-- sidebar/media -->
            @include('site.document.text.blocks.sidebar.media')
            <!-- /sidebar/media -->
            
            <!-- sidebar/natural -->
            {{-- @include('site.document.text.blocks.sidebar.natural') --}}
            {!! xdocument::recent('DocumentImageController', 5, 'Natural History Museum', URL::route('site.page.natural.history.museum'), array(34, 35, 36)) !!}
            <!-- /sidebar/natural -->
        </div><!-- col#1 -->
        <div class="col-md-4 col">
            <!-- sidebar/tourist -->
            {{-- @include('site.document.text.blocks.sidebar.tourist') --}}
            {!! xdocument::recent('DocumentTextController', 3, 'tourist information', URL::route('site.page.tourist.information'), array(98, 86, 129, 26, 25, 24)) !!}
            <!-- /sidebar/tourist -->
           
            <!-- sidebar/real -->
            {!! xdocument::recent('DocumentImageController', 4, 'Photos/Vintage Portraits', URL::route('site.page.photos.vintage.portraits.people'), array(52)) !!}
            <!-- /sidebar/real -->
        </div><!-- col#2 -->
        
        <div class="col-md-4 col">
            <hr class="line gray mt40 clear">
            <h3 class="h3">Kythera map (Click to enlarge)</h3>

            <a href="/assets/img/map-kythera-en-small.jpg" data-lightbox="map" xdata-type="half" data-title="Kythera map with points of interest.">
               <img src="/assets/img/map-kythera-en.jpg" alt="Kythera map with points of interest." width="340px" height="500px" >
            </a>
            <br/>
           
            <!-- sidebar/board -->
            @include('site.document.text.blocks.sidebar.message')
            <!-- /sidebar/board -->
        </div><!-- col#3 -->
    </div>
</div>