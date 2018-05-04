@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">@lang('messages.Role')</div>
			<div class="card-body">
				<form method="GET" action="{{ $action }}">
					@csrf
					<input type="hidden" id="_method" name="_method" value="{{ $method }}">
					<input type="hidden" name="page" value="{{ $page }}">
					@foreach ($filters->keys() as $filterKey)
						<input type="hidden" name="{{$filterKey}}" value="{{ $filters->get($filterKey) }}">
					@endforeach
					<div class="form-group row">
						<label for="name" class="col-md-4 col-form-label text-md-right">@lang('messages.Name')</label>
						<div class="col-md-6">
							<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $command->name }}" autofocus readonly>
						</div>
					</div>
					
					<div class="form-group row">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
								  <tr>
									<th>@lang('messages.Profiles')</th>
								  </tr>
								</thead>
								<tbody>
									@foreach ($command->profiles as $profile)
										<tr id="profile_{{$profile->id}}">
											<td>{{ $profile->name}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					
					 <div class="form-group row">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
								  <tr>
									<th>@lang('messages.Resources')</th>
								  </tr>
								</thead>
								<tbody>
									@foreach ($command->resources as $resource)
										<tr id="resource_{{$resource->id}}">
											<td>{{ $resource->display_name}}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					<div class="form-group row mb-0">
						@include('admin.down_buttons', ['btn_save' => false])
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
