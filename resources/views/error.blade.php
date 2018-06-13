@extends('layouts.app')
@section('header')
<ul class="navbar-nav mr-auto"></ul>
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-2">
		<div class="d-none d-md-block navbar-expand-md">
			@include('actions', ['sourceUrl' => '/home', 'collapse' => '', 'navactions_id' => 'navactions'])
		</div>
	</div>
	<div class="col-md-10">
		<div class="card">
			<div class="card-body"><div class="row"><div class="container">@include('common_status')</div></div></div>
		</div>
	</div>
</div>
@endsection
@section('footer')
@include('footer')
@endsection
