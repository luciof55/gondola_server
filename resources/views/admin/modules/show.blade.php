@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">@lang('messages.Module')</div>

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
						<label for="role" class="col-md-4 col-form-label text-md-right">@lang('messages.Role')</label>
						<div class="col-md-6">
							<input id="role" type="text" class="form-control" name="role" value="{{ $command->role->name }}" readonly>
						</div>
					</div>
					<div class="form-group row">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
								  <tr>
									<th>@lang('messages.Modules')</th>
								  </tr>
								</thead>
								<tbody>
									@foreach ($command->submodules as $submodule)
										<tr id="submodule_{{$submodule->id}}">
											<td>{{ $submodule->name}}</td>
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
