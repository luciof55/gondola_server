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
		<div class="card">
			<div class="card-header"><nav class="navbar navbar-expand-sm navbar-dark">NAVBAR</nav></div>
			<div class="card-body">
				<div class="row"><div class="container">@include('common_status')</div></div>
				<div class="row">
					<div class="container">
						<passport-clients></passport-clients>
						<passport-authorized-clients></passport-authorized-clients>
						<passport-personal-access-tokens></passport-personal-access-tokens>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer')
@include('footer')
@endsection
