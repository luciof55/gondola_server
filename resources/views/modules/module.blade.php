@extends('layouts.app')
@section('header')
<nav class="navbar navbar-expand-sm navbar-light bg-light">
	@if (isset($subModulesMenuItem))
		<ul class="navbar-nav mr-auto">
		@foreach($subModulesMenuItem as $menuItem)
			<li class="nav-item active"><a class="nav-link" href="{{$menuItem['url']}}">{{$menuItem['text']}}</a></li>
		@endforeach
		</ul>
	@endif
</nav>
@endsection
@section('content')
<div class="row justify-content-center">
	<div class="col-md-2">
		@include('actions')
	</div>
	<div class="col-md-10">
		<div class="card">
			<div class="card-header"><nav class="navbar navbar-expand-sm navbar-dark">MODULES</nav></div>
			<div class="card-body">
				<div class="row"><div class="container">@include('common_status')</div></div>
				<div class="row">
					<div class="container">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
								  <tr>
									<th>@lang('messages.Module')</th>
								  </tr>
								</thead>
								<tbody>
									@foreach ($subModules as $command)
										<tr id="{{$entity}}_{{$command->id}}" onclick="crudInstance.setCurrentRowId('{{$entity}}_{{$command->id}}');">
											<td>{{ $command->name }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="container">
						<div class="float-right">
							<div class="btn-group">
								<button id="button_new" type="button" class="btn btn-primary btn-md">Configurar</button>
							</div>
						</div>
					</div>
				</div>
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