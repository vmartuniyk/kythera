@extends('site.layout.default')

@section('title')
GEDCOM
@stop

@section('style')
ul {padding-left:10px;}
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
            <div class="crumb pull-right"><a href="{!!action('GedcomController@getIndidata', $gedcom->id)!!}">Back to individuals</a>&nbsp;OR&nbsp;</div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
        
			<p>
            @if($gedcom->system)
			    Showing <b>{!!$individual->gedcom_key!!}</b> individual for <em>{{ $gedcom->file_name }}</em>, 
			    created by {{ $gedcom->system->product_name }} ({{ $gedcom->system->version_number }})
			    @if($gedcom->system->corporation)
			    , {{ $gedcom->system->corporation }}.
			    @endif
		    @endif
			</p>
			            

            <table class="table table-striped">
                <tr>
                    <th>GEDOM key</th>
                    <td>{{ $individual->gedcom_key }}</td>
                </tr>     
                <tr>
                    <th>Firstname</th>
                    <td>{{ $individual->first_name }}</td>
                </tr>
                <tr>
                    <th>Lastname</th>
                    <td>{{ $individual->last_name }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ strtoupper($individual->sex) }}</td>
                </tr>
                @if (0&&$individual->age())
                <tr>
                    <th>Age of death</th>
                    <td>{{ $individual->age() }}</td>
                </tr>
                @endif
                @if (0)
                <tr>
                    <th>Notes</th>
                    <td><ul>
                    @foreach($notes as $note)
                        <li> {!!$note->note!!}</li>
                    @endforeach
                    </ul></td>
                </tr>
                @endif
                <tr>
                    <th>Relations</th>
                    <td>
			            <ul>
	                        @if(!$individual->families->isEmpty()) 
			                @foreach($individual->families AS $family)
	                        <li>As child <a href="{!!action('GedcomController@getFamily', $family->id)!!}">{!!$family->gedcom_key!!}</a></li>
			                @endforeach
	                        @endif
			            
				            @if(!$individual->familiesAsHusband->isEmpty())
			                @foreach($individual->familiesAsHusband AS $family)
	                        <li>As husband <a href="{!!action('GedcomController@getFamily', $family->id)!!}">{!!$family->gedcom_key!!}</a></li>
			                @endforeach
				            @endif
	                    
		   	                @if(!$individual->familiesAsWife->isEmpty())
			                @foreach($individual->familiesAsWife AS $family)
	                        <li>As wife <a href="{!!action('GedcomController@getFamily', $family->id)!!}">{!!$family->gedcom_key!!}</a></li>
			                @endforeach
	    		            @endif
	                    </ul>
                    </td>
                </tr>
            </table>
        
            @if(0)
            @if (count($events))
            <table class="table table-striped">
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Place / Value</th>
                    <th>Notes</th>
                </tr>
                @foreach($events as $event)
                <tr>
                    <td>{!!$event->event!!}</td>
                    <td>{!!$event->date!!}</td>
                    <td>{!!$event->getEventValue()!!}</td>
                    <td>{!!$event->note!!}</td>
                </tr>
                @endforeach
            </table>
            @endif
            @endif

        
        
        </div>
    </div>
@stop