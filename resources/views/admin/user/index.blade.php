@extends("admin.layout.default")

@section("content")

<div class="container">

		@php
		$type = Input::get('type', 'active');
		$ok = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M21.855 10.303c.086.554.145 1.118.145 1.697 0 6.075-4.925 11-11 11s-11-4.925-11-11 4.925-11 11-11c2.348 0 4.518.741 6.304 1.993l-1.421 1.457c-1.408-.913-3.083-1.45-4.883-1.45-4.963 0-9 4.038-9 9s4.037 9 9 9c4.894 0 8.879-3.928 8.99-8.795l1.865-1.902zm-.951-8.136l-9.404 9.639-3.843-3.614-3.095 3.098 6.938 6.71 12.5-12.737-3.096-3.096z"/></svg>';
		@endphp

		<a href="users?type=active" class="btn btn-success @if($type == 'active') active @endif" role="button">
			Active @if($type == 'active') {!! $ok !!} @endif
		</a>
		&nbsp;

		<a href="users?type=unconfirmed" class="btn btn-warning @if($type == 'unconfirmed') active @endif" role="button">
			Unconfirmed @if($type == 'unconfirmed') {!! $ok !!} @endif
		</a>
		&nbsp;

		<a href="users?type=trashed" class="btn btn-danger @if($type == 'trashed') active @endif" role="button">
			Trashed @if($type == 'trashed') {!! $ok !!} @endif
		</a>
		&nbsp;
		<a href="users?type=admin" class="btn btn-primary @if($type == 'admin') active @endif" role="button">
			Admin @if($type == 'admin') {!! $ok !!} @endif
		</a>
		&nbsp;
		<a href="users?type=all" class="btn btn-info @if($type == 'all') active @endif" role="button">
			All @if($type == 'all') {!! $ok !!} @endif
		</a>

		{!! $dataTable->table() !!}

</div>

@endsection


@section('javascript')
		{!! xhtml::script('assets/vendors/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js') !!}
		{!! xhtml::script('assets/vendors/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js') !!}
		{!! xhtml::script('vendor/datatables/buttons.server-side.js') !!}
		{!! $dataTable->scripts() !!}
		<script type="text/javascript">
		$('.btn').button();
		</script>
@endsection
