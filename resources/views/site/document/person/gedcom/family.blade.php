@extends('site.layout.default')

@section('title')
GEDCOM
@stop

@section('style')
@stop

@section('javascript')
@stop

@section('content')
    <div class="container">
        <div class="head">
            <h1 class="pull-left">
            GEDCOM
            </h1>
            <div class="crumb pull-right"><a href="{!!action('GedcomController@getSummary', $gedcom->id)!!}">Back to import summary</a></div>
            <div class="crumb pull-right"><a href="{!!action('GedcomController@getFamidata', $gedcom->id)!!}">Back to families</a>&nbsp;OR&nbsp;</div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
        
            <p>
                Showing family <b>{!! $family->gedcom_key !!}</b> for <em>{{ $gedcom->file_name }}</em>, 
                created by {{ $gedcom->system->product_name }} ({{ $gedcom->system->version_number }})
                @if($gedcom->system->corporation)
                , {{ $gedcom->system->corporation }}.
                @endif
            </p>
                        

			<table class="table table-striped">
				<tr>
					<th>GEDCOM key</th>
					<td>{{ $family->gedcom_key }}</td>
				</tr>
				<tr>
					<th>Husband ID</th>
					<td>{!! $husband ? $husband->first_name . ' ' . $husband->last_name . ' (<a href="'. action('GedcomController@getIndividual', array($family->indi_id_husb, $gedcom->id)) .'">'. $husband->gedcom_key .'</a>)' : '' !!}</td>
				</tr>
				<tr>
					<th>Wife ID</th>
					<td>{!! $wife ? $wife->first_name . ' ' . $wife->last_name . ' (<a href="'. action('GedcomController@getIndividual', array($family->indi_id_wife, $gedcom->id)) .'">'. $wife->gedcom_key .'</a>)' : '' !!}</td>
				</tr>
				<tr>
					<th>Children</th>
					<td>{{ $family->children()->count() }}</td>
				</tr>
				@foreach ($family->children as $child)
				<tr>
					<th></th>
					<td>{!! $child->first_name . ' ' . $child->last_name . ' (<a href="'. action('GedcomController@getIndividual', array($child->id, $gedcom->id)) .'">'. $child->gedcom_key .'</a>)'!!}</td>
				</tr>
				@endforeach
			</table>
        
            @if (count($events))
            <table class="table table-striped">
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Place / Value</th>
                </tr>
                @foreach($events as $event)
                <tr>
                    <td>{!!$event->event!!}</td>
                    <td>{!!$event->date!!}</td>
                    <td>{!!$event->getEventValue()!!}</td>
                </tr>
                @endforeach
            </table>
            @endif
        
        
        
        </div>
    </div>
@stop