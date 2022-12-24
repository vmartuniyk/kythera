@extends('site.layout.default-new')

@section('title')
	{{ $page->title }} - {{ $item->title }}
@stop

@section('style')
ul.villages {list-style:none;padding-left:14px}
ul.villages {margin-bottom:40px;}
#map {width:100%;min-height:550px;}
.map {margin-top:87px}
@stop

@section('content')
	<div class="inner-page">
		<div class="inner-page__container">
			<div class="inner-page__wrap">

				@include('partials.front.left-front-menu')

				<div class="inner-page__content content-inner text-first-screen">
					<div class="content-inner__wrap">
						<section class="content-inner__text inner-main-text">
							<div class="inner-main-text__label section-label">{{ $page->title }}</div>
							<p class="inner-main-text__paragraf">

								{!! $page->content !!}
							</p>

						</section>
						<div class="content">
							<div class="col-md-4">
								@include('site.page.village.blocks.view.content')
							</div>

							<div class="col-md-8 map">
								<div id="map"></div>
								<a target="_blank" href="https://www.google.es/maps/place/{{ $item->getAddress() }}">Enlarge map</a>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

@stop

@section('js')
{!! xhtml::script('assets/vendors/cdn/ajax/libs/jquery/2.1.3/jquery.min.js') !!}
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{env('GOOGLE_MAPS_API')}}&language={!!App::getLocale() == 'en' ? 'en' : 'el'!!}"></script>
<script type="text/javascript" src="/assets/vendors/hpneo/gmaps.js"></script>
<script>
    var map = null;

	jQuery(function() {
		//Show kythera capital Chora and center map
		map = new GMaps({
		  div: '#map',
		  zoom: 3,
		  lat: 40.4381311,
		  lng: -21.6092317,
		  zoom: 11,
		  lat: 36.1492961,
		  lng: 22.9877193,
		});
		map.setCenter(36.2542688,22.9725497);

		//Check for GEO info
		if ({!!$item->latitude ? $item->latitude : 0!!} && {!!$item->longitude ? $item->longitude : 0!!}) {
			map.addMarker({
				lat: {!!$item->latitude ? $item->latitude : 0!!},
				lng: {!!$item->longitude ? $item->longitude : 0!!},
				title: '{!!$item->village_name!!}',
				click: function(e) {}
			});
		} else {
			//Try to find village
			GMaps.geocode({
			  address: '{{ $item->getAddress() }}',
			  callback: function(results, status) {
			    if (status == 'OK') {
			      var latlng = results[0].geometry.location;
			      //map.setCenter(latlng.lat(), latlng.lng());
			      map.addMarker({
			        lat: latlng.lat(),
			        lng: latlng.lng(),
			        title: '{!!$item->village_name!!}',
			        click: function(e) {}
			      });
			    } else {
			    	//alert('{!!$item->village_name!!} not found. Please contact administrator.');
			    }
			  }
			});
		}
    });
</script>
@stop