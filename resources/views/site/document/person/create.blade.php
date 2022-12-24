@extends('site.layout.default')

@section('title')
	Create
@stop

@section('style')
.content .member {
	margin-left:10px;
}
.content .sidebar .member h2 {
    color: #00adf0;
    font: 14px/14px arial;
    margin-bottom: 10px;
    margin-top: 20px;
    margin-left:0;
}

.content p {
    color: #000;
    font: bold 14px/14px arial;
    margin: 0;
    padding-left: 10px;
}
@stop

@section('stylesheet')
@stop

@section('javascript')
@stop

@section('content')
    <div class="container">
        <div class="head clearfix">
            <h1 class="pull-left">
            New person
            </h1>
            <div class="crumb pull-right"><a href="{!!URL::previous()!!}">Back to family tree</a></div>
        </div>
        <div class="content">

            @if(Session::has('errors'))
            <div class="alert alert-dismissable alert-danger">
                <ul>
	            @foreach ($errors->all() as $error)
				    <li>{!!$error!!}</li>
                @endforeach
                </ul>
            </div>
            @endif            
            @if(Session::has('error'))
            <div class="alert alert-dismissable alert-danger">
                <ul>
				    <li>{!!Session::get('error')!!}</li>
                </ul>
            </div>
            @endif            
        
            <div class="col-md-8 col2">
                <div class="line"></div>
                <p>
				There are two ways to create a family tree: <br>
				1. Start with a person in the form below and after submitting it add members of the family in subsequent forms<br>
				or<br>
				2. Upload a gedcom file from your genealogy program with the "browse" and "send" buttons in the right column.
                </p>            
                <div class="line"></div>
            
	            <div style="padding:15px">
                {!! Form::open(array('action' => array('DocumentPersonController@store'), 'method' => 'POST', 'id' => 'person', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
	            {!! $form->build( \Kythera\Models\Person::MEMBER_PERSON ) !!}
	            {!! Form::close() !!}
	            </div>
            </div>
            
            <div class="col-md-4 sidebar">
                <hr class="line gray clear">
                <div class="member">
                    <h3 class="h3">GEDCOM</h3>
                        <span>Upload a gedcom file from your genealogy program with the "browse" and "send" buttons.</span>
                        
                        {!! Form::open(array('action' => array('GedcomUploadController@postUpload'), 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true)) !!}
                        {{--
						<div class="form-group">
						    <label class="control_label" for="tree_name">Tree name</label>
						    <input type="text" id="tree_name" name="tree_name" class="form-control">
						</div>
						<div class="form-group">
						    <label class="control_label" for="source">Source</label>
						    <input type="text" id="source" name="source" class="form-control">
						</div>
						<div class="form-group">
						    <label class="control_label" for="notes">Notes</label>
						    <textarea id="notes" rows="10" cols="50" name="notes" class="form-control" style="height: 90px"></textarea>
						</div>
                        --}}
                        <div class="form-group" style="margin-left:-10px">
                            <label class="control_label" for="notes">File</label>
                            <input type="file" name="uploads[]" multiple="1">
                        </div>
                        <div class="form-group">
						<input type="submit" class="btn btn-default blue pull-right" value="{{ trans('locale.button.send') }}" />
                        </div>
                        {!! Form::close() !!}
                </div>
                
                
            </div>            


        </div>
    </div>
@stop