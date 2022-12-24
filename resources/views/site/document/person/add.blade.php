@extends('site.layout.default')

@section('title')
        @if ($member == \Kythera\Models\Person::MEMBER_PARENT)
        	Add parents of {!!\Kythera\Models\Person::buildDescription($subject)!!}
        @elseif ($member == \Kythera\Models\Person::MEMBER_SPOUSE)
        	Add a spouse to {!!\Kythera\Models\Person::buildDescription($subject)!!}
        @elseif ($member == \Kythera\Models\Person::MEMBER_CHILD)
        	Add a child to the family of {!!\Kythera\Models\Person::buildDescription($subject)!!}
        @else
        @endif
@stop

@section('style')
.member {
	padding-right:30px
}
.form-horizontal .form-group {
    margin-left: -10px;
}
@stop

@section('stylesheet')
@stop

@section('javascript')
@stop

@section('content')
    <div class="container">
        <div class="head">
            <h1 class="pull-left">
		        @if ($member == \Kythera\Models\Person::MEMBER_PARENT)
		        	Add parents of {!!\Kythera\Models\Person::buildDescription($subject)!!}
		        @elseif ($member == \Kythera\Models\Person::MEMBER_SPOUSE)
		        	Add a spouse to {!!\Kythera\Models\Person::buildDescription($subject)!!}
		        @elseif ($member == \Kythera\Models\Person::MEMBER_CHILD)
		        	Add a child to the family of {!!\Kythera\Models\Person::buildDescription($subject)!!}
		        @else
		        @endif
            </h1>
            <div class="crumb pull-right"><a href="{!!URL::previous()!!}">Back to family tree</a></div>
        </div>
        <br class="clear"/>
        <hr class="thin"/>
        <div class="content">

		        @if ($member == \Kythera\Models\Person::MEMBER_PARENT)
		        	{!! Form::open(array('action' => array('DocumentPersonController@store'), 'method' => 'POST', 'id' => 'person', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
                    <div class="clearfix">
			            <div class="col-md-6">
			            	<div class="member">
			            		<h2>Mother</h2>
			            		Have you already entered the Person in this or another tree? If so please choose her from this pulldown menu. If not, please complete the form below and submit.
			            		<hr class="thin"/>
					            {!! $mother->build($member, false) !!}
			            	</div>
			            </div>
			            <div class="col-md-6">
			            	<div class="member">
			            		<h2>Father</h2>
			            		Have you already entered the Person in this or another tree? If so please choose her from this pulldown menu. If not, please complete the form below and submit.
			            		<hr class="thin"/>
					            {!! $father->build($member, false) !!}
			            	</div>
			            </div>
                    </div>
                    
		            <hr class="thin"/>
		            <div class="form-group">
			            <a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{!!trans('locale.button.cancel')!!}</a>
			            <button type="submit" class="btn btn-black pull-right">{!!trans('locale.button.save')!!}</button>
		            </div>
                    
                    {!! Form::close() !!}
                    
		            <div class="col-md-12"></div>
		        @elseif ($member == \Kythera\Models\Person::MEMBER_SPOUSE)
			        <div class="col-md-8">
		            	<div class="member">
		            		<h2>Spouse</h2>
		            		Have you already entered the Person in this or another tree? If so please choose her from this pulldown menu. If not, please complete the form below and submit.
		            		<hr class="thin"/>
		            		{!! Form::open(array('action' => array('DocumentPersonController@store'), 'method' => 'POST', 'id' => 'person', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
				            {!! $spouse->build($member) !!}
				            {!! Form::close() !!}
		            	</div>
	            	</div>
		        @elseif ($member == \Kythera\Models\Person::MEMBER_CHILD)
			        <div class="col-md-8">
		            	<div class="member">
		            		<h2>Child</h2>
		            		Please choose the other parent of the child before you proceed to fill out the form. If you haven't added the other parent yet, please return to the previous page and click on "Add Spouse".
		            		<hr class="thin"/>
		            		Have you already entered the child in this or another tree? If so please choose from this pulldown menu. If not, please complete the form below and submit.
		            		<hr class="thin"/>
		            		{!! Form::open(array('action' => array('DocumentPersonController@store'), 'method' => 'POST', 'id' => 'person', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
		            		{!! $child->build($member) !!}
				            {!! Form::close() !!}
		            	</div>
	            	</div>
		        @else
		        @endif

        </div>
    </div>
@stop