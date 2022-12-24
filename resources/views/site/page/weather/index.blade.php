@extends('site.layout.default')

@section('style')
.weather-wrapper {display:none;}
.weather-icon {padding-bottom:20px}
@stop

@section('title')
	{{ $page->title }}
@stop

@section('content')
    <div class="container">
        <div class="head">
            <h1 class="pull-left">{{ $page->title }}</h1>
          <div class="crumb pull-right"></div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
            <p>
            {!! $page->content !!}
            </p>


<div class="weather-wrapper"> <img src="" class="weather-icon" alt="Weather Icon" />
<p><strong>Place</strong> <br />
<span class="weather-place"></span></p>
<p><strong>Temperature</strong> <br />
<span class="weather-temperature"></span> (<span class="weather-min-temperature"></span> - <span class="weather-max-temperature"></span>)</p>
<p><strong>Description</strong> <br />
<span class="weather-description capitalize"></span></p>
<p><strong>Humidity</strong> <br />
<span class="weather-humidity"></span></p>
<p><strong>Wind speed</strong> <br />
<span class="weather-wind-speed"></span></p>
<p><strong>Sunrise</strong> <br />
<span class="weather-sunrise"></span></p>
<p><strong>Sunset</strong> <br />
<span class="weather-sunset"></span></p>
</div>


	<br/>
	<br/>
	<br/>
	<a href="/en/tourist-information/weather-daylight">Other weather widgets....</a>
        </div>
    </div>
@stop

@section('javascript')
<script src="/assets/vendors/weather/src/openWeather.js"></script>
<script>
jQuery(function() {
	//http://openweathermap.org/api
	jQuery('.weather-temperature').openWeather({
		city: 'Kythira, Greece', // city name, country / province/ state
		lat: 36.15, // defines the latitude
		lng: 22.9881, // defines the longitude
		key: '70a9ec08c352c2d030d0d2830bb12b38',
		units: "c", // defines the type of units (c or f).
		descriptionTarget: '.weather-description',
		windSpeedTarget: '.weather-wind-speed',
		minTemperatureTarget: '.weather-min-temperature',
		maxTemperatureTarget: '.weather-max-temperature',
		humidityTarget: '.weather-humidity',
		sunriseTarget: '.weather-sunrise',
		sunsetTarget: '.weather-sunset',
		placeTarget: '.weather-place',
		iconTarget: '.weather-icon',
		customIcons: '/assets/vendors/weather/src/img/icons/weather/',
		success: function() {
			//show weather
			jQuery('.weather-wrapper').show();
		},
	});
});
</script>
@stop