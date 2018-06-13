@extends('admin.layout')
@section('securityContent')
<div class="row justify-content-center">
	<div class="col-md-2">
		@include('admin.actions')
	</div>
	<div class="col-md-10">
		<div class="card">
			<div class="d-flex flex-column">
				<div class="p-2 text-white bg-info border-bottom rounded-top">Security</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="container">
						@include('common_status')
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
