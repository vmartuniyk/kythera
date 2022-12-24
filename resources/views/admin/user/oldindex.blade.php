@extends("admin.layout.default")

@section('style')
.permission-user {color:silver}
@stop

@section("content")

<div class="container">

	<h1>{!! trans('admin.users') !!} ({!! array_sum($letters) !!})</h1>

	<hr class="thin" />

	<ul class="pagination alpha">
		@foreach($letters as $letter => $n)
			<li class="{!! Session::get('users.query') == $letter ? 'active' : '' !!}"><a href="?l={!!$letter!!}">{!!$letter!!}</a></li>
		@endforeach
	</ul>

	{{-- @francesdath 2017-06-06 user search input --}}

	<form id="form-group" class="form-group admin-search">
		<input id='s' placeholder="Search &hellip;" class="form-control" name="s" type="search">
		<button type="submit" class="btn btn-default btn-black">Search</button>
	</form>

	<div class="admin-actions">

		{{-- @francesdath 2017-08-02 unconfirmed users search --}}
		<a href="?c=unconfirmed" type="button" name="unconfirmed" value="unconfirmed" class="btn btn-default admin-action"><i class="glyphicon glyphicon-check"></i></a>

		{{-- @francesdath 2017-06-06 admins search --}}
		<a href="?q=admin" type="button" name="admin" value="admin" class="btn btn-default admin-action"><i class="glyphicon glyphicon-user"></i></a>

	</div>

	<span class="pull-right">{!! $items->render() !!}</span>

	<table class="table table-striped table-hover table-condensed">
		<tr>
			{{-- @francesdath 2017-06-09 add td id if debug enabled --}}
			@if (Config::get('app.debug'))
				<td class="id">ID</td>
			@endif
			<th class="lastname">Lastname</th>
			<th class="firstname">Firstname</th>
			<th class="email">Email</th>
			<th class="role">Role</th>
			<th class="edit">Edit</th>
			<th class="disable">Disable</th>
		</tr>

		@foreach($items as $i=>$item)

			{{-- @francesdath 2017-08-02 add tr classes: administrator, unconfirmed --}}
			@if ( $item->id )
				@if ( $item->hasRole( 'administrator' ) )
					<tr class="administrator">
				@elseif ( ! $item->confirmed )
					<tr class="unconfirmed">
				@else
					<tr>
				@endif
			@endif

				@if (Config::get('app.debug'))
					<td class="id">{{ $item->id }}</td>
				@endif

				<td class="lastname">{{ $item->lastname }}</td>
				<td class="firstname">{{ $item->firstname }}</td>
				<td class="email">{{ $item->email }}

					{{-- @francesdath 2017-08-02 add span for unconfirmed button --}}

					@if ( ! $item->confirmed )
						<span class="confirm-user"><a href="{!! action('Admin\AdminUserController@action', array($item->id, 'confirm')) !!}" title="Confirm User">Confirm User <i class="glyphicon glyphicon-ok"></i></a></span>
					@endif

				</td>

				@if ($item->id)

					@if ($item->hasRole('administrator'))
						<td class="role administrator"><a href="{!! action('Admin\AdminUserController@action', array($item->id, 'degrade')) !!}" title="Remove administrator permission"><i class="glyphicon glyphicon-user"></a></td>
					@else
						<td class="role"><a href="{!! action('Admin\AdminUserController@action', array($item->id, 'promote')) !!}" title="Add administrator permission"><i class="glyphicon glyphicon-user permission-user"></a></td>
					@endif

					{{-- @francesdath 2017-06-07 add td user edit --}}
					<td class="edit"><a href="{!!action('Admin\AdminUserController@edit',$item->id)!!}"><i class="glyphicon glyphicon-pencil create"></i></a></td>

					@if ($item->deleted_at)
						<td class="enable"><a href="{!! action('Admin\AdminUserController@action', array($item->id, 'enable')) !!}" title="Enable user"><i class="glyphicon glyphicon-off"></a></td>
					@else
						<td class="disable"><a href="#" title="Disable user"
							data-toggle="modal"
							data-target="#confirm-delete"
							data-id={!!$item->id!!}
							data-title="{!! trans('locale.delete.confirm') !!}"
							data-message="{{ trans('locale.delete.confirm.question', array('value'=>Auth::user()->full_name)) }}"
							data-action="{!! route('admin.user.destroy', $item->id) !!}"><i class="glyphicon glyphicon-trash"></i></a>
						</td>
					@endif

				@endif

			</tr>

		@endforeach

	</table>

	<span class="pull-right">{!! $items->render() !!}</span>

	<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				{!! Form::open(array('method'=>'DELETE')) !!}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">{title}</h4>
					</div>
					<div class="modal-body">
						<p>{message}</p>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger danger">{{ trans('locale.button.delete') }}</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('locale.button.cancel') }}</button>
					</div>
					{!! Form::token() !!}
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@stop
