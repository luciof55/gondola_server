@extends('layouts.app')
@section('header')
<nav class="navbar navbar-expand-sm navbar-light bg-light">
	UN MENU
</nav>
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-2">
		@include('actions')
	</div>
	<div class="col-md-10">
		<div class="card">
			<div class="card-header"><nav class="navbar navbar-expand-sm navbar-dark">NAVBAR</nav></div>
			<div class="card-body">
				@if (session('unauthorized'))
					<div class="alert alert-danger">
						{{ session('unauthorized') }}
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer')
<div class="row justify-content-center">
	FOOTER
</div>
@endsection