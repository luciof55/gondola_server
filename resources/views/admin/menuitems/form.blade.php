@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">@lang('messages.MenuItem')</div>
			<div class="card-body">
				<form id="commandForm" method="POST" action="{{ $action }}">
					@csrf
					<input id="id" name="id" type="hidden" value="{{ $command->id }}">
					<input type="hidden" id="_method" name="_method" value="{{ $method }}">
					<input type="hidden" id="page" name="page" value="{{ $page }}">
					@foreach ($filters->keys() as $filterKey)
						<input type="hidden" name="{{$filterKey}}" value="{{ $filters->get($filterKey) }}">
					@endforeach
					@if (isset($command->resource))
						<?php $resource_id = $command->resource->id; ?>
					@else
						<?php $resource_id = null; ?>
					@endif
					@if (!isset($command->id))
						<div class="form-group row">
							<label for="resource_id" class="col-md-4 col-form-label text-md-right">@lang('messages.Resource')</label>
							<div class="col-md-6">
								@if ($errors->has('resource_id'))
									{{ Form::select('resource_id', $resources, $resource_id, ['required', 'autofocus', 'placeholder' => 'Pick a resource...', 'class' => 'form-control is-invalid'])}}
								@else
									{{ Form::select('resource_id', $resources, $resource_id, ['required', 'autofocus', 'placeholder' => 'Pick a resource...', 'class' => 'form-control'])}}
								@endif
								@if ($errors->has('resource_id'))
									<span class="invalid-feedback">
										<strong>{{ $errors->first('resource_id') }}</strong>
									</span>
								@endif
							</div>
						</div>
					@else
						<div class="form-group row">
							<label for="resource" class="col-md-4 col-form-label text-md-right">@lang('messages.Resource')</label>
							<div class="col-md-6">
								<input id="resource" type="text" class="form-control" name="resource" value="{{ $command->resource->display_name }}" autofocus readonly>
							</div>
						</div>
					@endif
					<div class="form-group row">
						<label for="type" class="col-md-4 col-form-label text-md-right">@lang('messages.MenuType')</label>
						<div class="col-md-6">
							@if ($errors->has('type'))
								{{ Form::select('resource_id', $types, $command->type, ['required', 'placeholder' => 'Pick a type...', 'class' => 'form-control is-invalid'])}}
							@else
								{{ Form::select('type', $types, $command->type, ['required', 'placeholder' => 'Pick a type...', 'class' => 'form-control'])}}
							@endif
							@if ($errors->has('type'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('type') }}</strong>
								</span>
							@endif
						</div>
					</div>
					
					<div class="form-group row">
						<label for="url" class="col-md-4 col-form-label text-md-right">URL</label>

						<div class="col-md-6">
							<input id="url" type="text" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" name="url" value="{{ $command->url }}" >

							@if ($errors->any())
								 @foreach ($errors->all() as $error)
									<span class="invalid-feedback">
										<strong>{{ $error }}</strong>
									</span>
								 @endforeach
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
