@extends('layouts.app')
@section('header')
<ul class="navbar-nav mr-auto">
</ul>
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-2">
		@include('actions', ['sourceUrl' => '/home'])
	</div>
	<div class="col-md-10">
		<div class="container">
			<passport-clients></passport-clients>
		</div>
	</div>
</div>
@endsection
@section('footer')
@include('footer')
@endsection
