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
			<div class="card-header"><nav class="navbar navbar-expand-sm navbar-dark">{{$subModuleName}}</nav></div>
			<div class="card-body">
				<div class="row"><div class="container">@include('common_status')</div></div>
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