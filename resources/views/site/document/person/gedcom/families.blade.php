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
        <div class="content">
        
			<p>
			    Showing <b>{!!$count!!}</b> families for <em>{{ $gedcom->file_name }}</em>, 
			    created by {{ $gedcom->system->product_name }} ({{ $gedcom->system->version_number }})
			    @if($gedcom->system->corporation)
			    , {{ $gedcom->system->corporation }}.
			    @endif
			</p>
			            

			<table class="table table-striped">
                <tr>
                    <th>GEDCOM key</th><th>Husband ID</th><th>Husband name</th><th>Wife ID</th><th>Wife Name</th><th>Children</th>
                </tr>
                
                @foreach($families as $fami)
                <tr>
                    <td><a href="{!!action('GedcomController@getFamily', $fami->fa_id)!!}">{!!$fami->gedcom_key!!}</a></td>
                    <td>{!!$fami->hgk!!}</td>
                    <td>{!!$fami->husb_name!!}</td>
                    <td>{!!$fami->wgk!!}</td>
                    <td>{!!$fami->wife_name!!}</td>
                    <td><a href="{!!action('GedcomController@getFamily', $fami->fa_id)!!}">show</a></td>
                </tr>
                @endforeach
                
			</table>
        
        
        
        </div>
    </div>
@stop