@extends('site.layout.default')

@section('title')
GEDCOM
@stop

@section('style')
@stop

@section('javascript')
<script>
$(function() {
    $('tr').each(function(i, item){
        var uri = $(item).find('a').attr('href');
        $(item).click(function(){
            if (uri)
            document.location.href = uri;
        });
    });
});
</script>
@stop

@section('content')
    <div class="container">
        <div class="head">
            <h1 class="pull-left">
            GEDCOM
            </h1>
            <div class="crumb pull-right"><a href="{!!action('GedcomController@getSummary', $gedcom->id)!!}">Back to import summary</a></div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content gedcom">
        
			<p>
            @if($gedcom->system)
			    Showing <b>{!!$individuals->count()!!}</b> individuals for <em>{{ $gedcom->file_name }}</em>, 
			    created by {{ $gedcom->system->product_name }} ({{ $gedcom->system->version_number }})
			    @if($gedcom->system->corporation)
			    , {{ $gedcom->system->corporation }}.
			    @endif
		    @endif
			</p>
			            

			<table class="table table-hover">
                <tr>
                    <th>GEDCOM key</th><th>Firstname</th><th>Surname</th><th>Gender</th><th>Birth</th><th>Death</th>
                </tr>
                
                @foreach($individuals as $ind)
                <tr>
                    <td><a href="{!!action('GedcomController@getIndividual', array($ind->in_id, $gedcom->id))!!}">{!!$ind->gedcom_key!!}</a></td>
                    <td>{!!$ind->first_name!!}</td>
                    <td>{!!$ind->last_name!!}</td>
                    <td>{!!strtoupper($ind->sex)!!}</td>
                    <td>{!! ($birth = $ind->birth()) ? $birth->date . ($birth->place ? ' ('.$birth->place.')' : '')  : '' !!}</td>
                    <td>{!! ($death = $ind->death()) ? $death->date . ($death->place ? ' ('.$death->place.')' : '')  : '' !!}</td>
                </tr>
                @endforeach
                
			</table>
        
        
        
        </div>
    </div>
@stop