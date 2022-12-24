
				<a name="entry"></a>
        <h2>Add an entry to the guestbook</h2>
          {!! Form::open(array('action'=>'DocumentGuestbookController@create', 'method'=>'POST', 'id'=>'guestbook', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
          <div class="form-group">
						<div class="clearfix">
					        <div class="col-md-6 category-group">
												<div class="form-group">
		                        <label class="control-label" for="firstname">Firstname</label>
		                        <input class="form-control" type="text" name="firstname" id="firstname" value="{{ Input::old('firstname') }}"/>
		                        {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
												</div>
												<div class="form-group">
		                        <label class="control-label" for="surname">Surname</label>
		                        <input class="form-control" type="text" name="surname" id="surname" value="{{ Input::old('surname') }}"/>
		                        {!! $errors->first('surname', '<span class="help-block">:message</span>') !!}
												</div>

					        </div>
					        <div class="col-md-6 category-group">
												<div class="form-group">
		                        <label class="control-label" for="email">Email</label>
		                        <input class="form-control" type="text" name="email" id="entry[e]" value="{{ Input::old('email') }}"/>
		                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
												</div>
												<div class="form-group">
		                        <label class="control-label" for="city">City/Country</label>
		                        <input class="form-control" type="text" name="city" id="city" value="{{ Input::old('city') }}"/>
		                        {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
												</div>
					        </div>
						</div>
					</div>

            	    <div class="form-group">
                        <label class="control-label" for="message_content">Message</label>
                        <textarea class="form-control" name="message_content" id="message_content" rows="3">{{ Input::old('message_content') }}</textarea>
                        {!! $errors->first('message_content', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
						<div class="xcategory-groups clearfix">
					        <div class="col-md-6 category-group">

							    <label xstyle="font-weight: 100" class="control-label" for="village_id">Family Village Name</label>
							    <select class="form-control" name="village_id" id="village_id">
							    	<option value="0">-</option>
	   							    @foreach($villages as $village)
	   							    <option value="{!! $village->id !!}"  {!! Input::old('village_id') == $village->id ? 'selected="selected"' : '' !!}>{!! $village->village_name !!}</option>
	   							    @endforeach
							    </select>

					        </div>
					        <div class="col-md-6 category-group">
					        	&nbsp;
					        </div>
						</div>
					</div>

					@if (!Auth::user())
    	    <div class="form-group">
			        <label xstyle="font-weight: 100" class="control-label">Captcha</label>
			        {!! app('captcha')->render(); !!}
    	    </div>
    	    @endif

                    <hr class="thin"/>
                    <div class="form-group">
        				<a class="btn btn-cancel btn-default" href="{!!URL::previous()!!}">{{ trans('locale.button.cancel') }}</a>
        				<button id="next" type="submit" class="btn btn-black pull-right">{{ trans('locale.button.save') }}</button>
                    </div>
          {!! Form::close() !!}


@section('javascript')

<!-- Laravel Javascript Validation -->
{!! $validator->selector('#guestbook') !!}

<script>
  $(document).ready(function() {
      //g-recaptcha invisible
      if($('#_g-recaptcha').length > 0){
          _beforeSubmit = function() {
              if($('#guestbook').valid())
                  return true;

              return false;
          }
      }
  });
</script>

@endsection
