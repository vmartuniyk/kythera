@extends('site.layout.default')

@section('title')
	Edit {!!\Kythera\Models\Person::buildDescription($subject)!!}
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

.content .sidebar .member ul {list-style:none;padding-left:10px}
.content .sidebar .member li {padding:1px 5px}
.content .sidebar .member li a:first-child {color:black}
.content .sidebar .member li:hover {background-color:#efefef}

.sidebar .member a {text-decoration:none;}
@stop

@section('stylesheet')
@stop

@section('javascript')
@stop

@section('content')
    <div class="container">
        <div class="head clearfix">
            <h1 class="pull-left">
            Edit {!!\Kythera\Models\Person::buildDescription($subject)!!}
            </h1>
            <div class="crumb pull-right"><a href="{!!action('DocumentFamilyController@getEntry', $subject->entry_id)!!}">&gt; Family tree</a></div>
        </div>
        <div class="content">

            <div class="col-md-8 col2">
            	<div class="line"></div>
            	<div class="member">
					@if(Session::has('global'))
					<div class="alert alert-success" role="alert">{!! Session::get('global') !!}</div>
					@endif
					@if(Session::has('success'))
					<div class="alert alert-success" role="alert">{!! Session::get('success') !!}</div>
					@endif
					@if(Session::has('error'))
					<div class="alert alert-danger" role="alert">{!! Session::get('error') !!}</div>
					@endif

            		{!! Form::open(array('action' => array('DocumentPersonController@store'), 'method' => 'POST', 'id' => 'person', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) !!}
		            {!! $form->build( \Kythera\Models\Person::MEMBER_PERSON ) !!}
		            {!! Form::close() !!}
            	</div>
            </div>

            <div class="col-md-4 sidebar">
            	<hr class="line gray clear">
            	<div class="member">
	            	<h3 class="h3">Family members</h3>

                    <div class="clearfix">
		            	<h2>Parents</h2>
		            	<ul class="clearfix">
		            	@forelse($parents as $parent)
		            		<li>
		            		@if (Config::get('app.debug'))
		            		({!!$parent->persons_id!!})
		            		@endif
		            		<a href="{!!action('DocumentPersonController@edit', $parent->did)!!}">{!!\Kythera\Models\Person::buildDescription($parent)!!}</a>  <a style="display:none;" class="pull-right" href="{!!action('DocumentPersonController@delete', array($personsId, \Kythera\Models\FamilyPerson::MEMBER_PARENT, $parent->id))!!}">delete</a></li>
		            	@empty
		            		<li>No parents</li>
		            	@endforelse
		            	</ul>
	                    <a class="btn btn-default blue pull-right" href="{!! action('DocumentPersonController@add', array($subject->id, \Kythera\Models\Person::MEMBER_PARENT))!!}" title="Add parent">Add parent</a>
                    </div>
                    
                    <hr/>
                    <div class="clearfix">
		            	<h2>Spouses</h2>
		            	<ul>
		            	@forelse($spouses as $spouse)
		            		<li>
		            		@if (Config::get('app.debug'))
		            		({!!$spouse->persons_id!!})
		            		@endif
		            		<a href="{!!action('DocumentPersonController@edit', $spouse->did)!!}">{!!\Kythera\Models\Person::buildDescription($spouse)!!}</a>  <a style="display:none;" class="pull-right" href="{!!action('DocumentPersonController@delete', array($personsId, \Kythera\Models\FamilyPerson::MEMBER_PARTNER, $spouse->id))!!}">delete</a></li>
		            	@empty
		            		<li>No spouse</li>
		            	@endforelse
		            	</ul>
	                    <a class="btn btn-default blue pull-right" href="{!! action('DocumentPersonController@add', array($subject->id, \Kythera\Models\Person::MEMBER_SPOUSE))!!}" title="Add spouse">Add spouse</a>
                    </div>

                    <hr>
                    <div class="clearfix">
		            	<h2>Children</h2>
		            	<ul class="clearfix">
		            	@forelse($children as $child)
		            		<li>
		            		@if (Config::get('app.debug'))
		            		({!!$child->persons_id!!})
		            		@endif
		            		<a href="{!!action('DocumentPersonController@edit', $child->did)!!}">{!!\Kythera\Models\Person::buildDescription($child)!!}</a>  <a style="display:none;" class="pull-right" href="{!!action('DocumentPersonController@delete', array($personsId, \Kythera\Models\FamilyPerson::MEMBER_CHILD, $child->id))!!}">delete</a></li>
		            	@empty
		            		<li>No children</li>
		            	@endforelse
		            	</ul>
	                    <a class="btn btn-default blue pull-right" href="{!! action('DocumentPersonController@add', array($subject->id, \Kythera\Models\Person::MEMBER_CHILD))!!}" title="Add child">Add child</a>
                    </div>
            	</div>

                    <br/>                
	            	<hr class="line gray clear">
	            	<div class="member">
		            	<h3 class="h3">Invitations</h3>
		            	<label>Invite someone to collaborate on {!!xhtml::fullname($subject)!!}</label>
                        {!! Form::open(array('action' => array('DocumentPersonController@invite'), 'method' => 'POST', 'class' => 'form-inline')) !!}
			            	<input type="text" name="email" class="form-control" placeholder="email@address.com" style="width:79%;" />
                            
                            {{--
                            <select name="email" class="form-control" style="width:100%; display:none;">
                            @foreach($members as $member)
                                <option value="{!!$member->email!!}">{!!$member->lastname!!}, {!!$member->firstname!!} ({!!$member->email!!})</option>
                            @endforeach
                            </select>
                            --}}
                            
                            <input type="hidden" name="entryId" value="{!!$subject->entry_id!!}" />
							<input type="submit" class="btn btn-default blue pull-right" value="{{ trans('locale.button.send') }}" />
		            	{!! Form::close() !!}
            	   </div>
                
            </div>

        </div>
    </div>
@stop