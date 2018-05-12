@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@else
	@if(isset($status))
		<div class="alert alert-success">
			{{ $status }}
		</div>
	@endif
	@if (session('statusError'))
		<div class="alert alert-danger">
			{{ session('statusError') }}
		</div>
	@endif
@endif