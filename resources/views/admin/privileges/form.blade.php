@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">@lang('messages.Privilege')</div>
			<div class="card-body">
				<form id="commandForm" method="POST" action="{{ $action }}">
					@csrf
					<input id="id" name="id" type="hidden" value="{{ $command->id }}">
					<input type="hidden" id="_method" name="_method" value="{{ $method }}">
					<input type="hidden" id="page" name="page" value="{{ $page }}">
					@foreach ($filters->keys() as $filterKey)
						<input type="hidden" name="{{$filterKey}}" value="{{ $filters->get($filterKey) }}">
					@endforeach
					<div class="form-group row">
						<label for="resource_id" class="col-md-4 col-form-label text-md-right">@lang('messages.Resource')</label>
						<div class="col-md-6">
							@if ($errors->has('resource_id'))
								{{ Form::select('resource_id', $resources, null, ['required', 'autofocus', 'placeholder' => 'Pick a resource...', 'class' => 'form-control is-invalid'])}}
							@else
								{{ Form::select('resource_id', $resources, null, ['required', 'autofocus', 'placeholder' => 'Pick a resource...', 'class' => 'form-control'])}}
							@endif
							@if ($errors->has('resource_id'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('resource_id') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="form-group row">
						<label for="role_id" class="col-md-4 col-form-label text-md-right">@lang('messages.Role')</label>
						<div class="col-md-6">
							@if ($errors->has('role_id'))
								{{ Form::select('role_id', $roles, null, ['required', 'autofocus', 'placeholder' => 'Pick a role...', 'class' => 'form-control is-invalid'])}}
							@else
								{{ Form::select('role_id', $roles, null, ['required', 'autofocus', 'placeholder' => 'Pick a role...', 'class' => 'form-control'])}}
							@endif
							@if ($errors->has('role_id'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('role_id') }}</strong>
								</span>
							@endif
						</div>
					</div>
					
					<div class="form-group row mb-0">
						@include('admin.down_buttons', ['btn_save' => true])
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
