@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">@lang('messages.Module')</div>
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
						<label for="name" class="col-md-4 col-form-label text-md-right">@lang('messages.Name')</label>
						<div class="col-md-6">
							<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $command->name }}" required autofocus>

							@if ($errors->any())
								 @foreach ($errors->all() as $error)
									<span class="invalid-feedback">
										<strong>{{ $error }}</strong>
									</span>
								 @endforeach
							@endif
						</div>
					</div>
					
					<div class="form-group row">
						<label for="profile_id" class="col-md-4 col-form-label text-md-right">@lang('messages.Profile')</label>
						<div class="col-md-6">
							@if ($errors->has('profile_id'))
								{{ Form::select('profile_id', $profiles, null, ['placeholder' => 'Pick a profile...', 'class' => 'form-control is-invalid'])}}
							@else
								{{ Form::select('profile_id', $profiles, null, ['placeholder' => 'Pick a profile...', 'class' => 'form-control'])}}
							@endif
							@if ($errors->has('profile_id'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('profile_id') }}</strong>
								</span>
							@endif
						</div>
					</div>
					
					<div class="form-group row">
						<label for="parent_id" class="col-md-4 col-form-label text-md-right">@lang('messages.Module')</label>
						<div class="col-md-6">
							@if ($errors->has('parent_id'))
								{{ Form::select('parent_id', $modules, null, ['placeholder' => 'Pick a module...', 'class' => 'form-control is-invalid'])}}
							@else
								{{ Form::select('parent_id', $modules, null, ['placeholder' => 'Pick a module...', 'class' => 'form-control'])}}
							@endif
							@if ($errors->has('parent_id'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('parent_id') }}</strong>
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
