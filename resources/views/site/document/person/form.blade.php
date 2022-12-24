				<div class="alert alert-success" role="alert">
					Press 'Save' at the bottom to continue
					(this is just a dummy form, please don't enter real data since it's not saved).
				</div>


				{!! Form::open(array('action' => array('DocumentFamilyController@edit', $subject->id), 'method' => 'POST', 'id' => 'subject', 'class' => 'form-horizontal')) !!}

                @foreach($fields as $i=>$field)
                	@if ($field->type == 'text')
	                <div class="form-group">
                        <label class="control-label">{!!$field->name!!}</label>
                        <input class="form-control" type="text" name="{!!$field->name!!}" value="{!!$field->value!!}"/>
                    </div>
                    @else
	                <div class="form-group">
                        <label class="control-label">{!!$field->name!!}</label>
                        <textarea class="form-control" name="{!!$field->name!!}">{!!$field->value!!}</textarea>
                    </div>
                    @endif
                @endforeach

                <div class="form-group">
   					<a class="btn btn-black pull-right" href="{!!URL::previous()!!}">{{ trans('locale.button.save') }}</a>
                </div>

                {!! Form::close() !!}
