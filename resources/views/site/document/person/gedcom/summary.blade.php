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
            <div class="crumb pull-right"><a href="{!! action('DocumentPersonController@create') !!}">Create family tree entry</a></div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">
        
            <p class="bg-info" style="margin-bottom:20px">
            Please validate the data, before submitting.
            </p>
        
			<p>
            @if($gedcom->system)
                <br>
                <br>
			    Showing statistics for <em>{{ $gedcom->file_name }}</em>, 
			    created by {{ $gedcom->system->product_name }} ({{ $gedcom->system->version_number }})
			    @if($gedcom->system->corporation)
			    , {{ $gedcom->system->corporation }}.
			    @endif
            @endif    
			</p>

            {!! Form::open(array('action' => array('GedcomController@postImport', $gedcom->id), 'method' => 'POST', 'class' => 'form-horizontal')) !!}
			<table class="table table-striped">
			    <tr>
			        <th>Individuals</th>
			        <td>{{ $statistics['all_ind'] }} ({!! HTML::link(action('GedcomController@getIndidata', $gedcom->id), 'show') !!})</td>
			    </tr>
			    <tr>
			        <th>Males and females</th>
			        <td>Males: {{ $statistics['males'] }}, 
			            Females: {{ $statistics['females'] }}, 
			            Unknowns: {{ $statistics['unknowns'] }}
			        </td>
			    </tr>
			    <tr>
			        <th>Birth dates</th>
			        <td>Earliest: {!! $statistics['min_birth'] ? $statistics['min_birth'] : '<em>unknown</em>' !!}, 
			            Latest: {!! $statistics['max_birth'] ? $statistics['max_birth'] : '<em>unknown</em>'  !!}
			        </td>
			    </tr>
			    <tr>
			        <th>Death dates</th>
			        <td>Earliest: {!! $statistics['min_death'] ? $statistics['min_death'] : '<em>unknown</em>'!!}, 
			            Latest: {!! $statistics['max_death'] ? $statistics['max_death'] : '<em>unknown</em>' !!}
			        </td>
			    </tr>
			    <tr>
			        <th>Families</th>
			        <td>{{ $statistics['all_fami'] }} {!! $statistics['all_fami'] ? HTML::link(action('GedcomController@getFamidata', $gedcom->id), '(show)') : '' !!}</td>
			    </tr>
                @if ($statistics['all_fami'])
			    <tr>
			        <th>Families with children</th>
			        <td>{{ $statistics['fams_with_children'] }} </td>
			    </tr>
                @endif
                
			</table>
     
            <div class="form-group">
            <input type="submit" class="btn btn-black pull-right" value="{{ trans('locale.button.import') }} {{ $gedcom->file_name }}" />
            </div>
            {!! Form::close() !!}
     
        
        </div>
    </div>
@stop