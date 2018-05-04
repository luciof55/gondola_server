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
						<label for="profile" class="col-md-4 col-form-label text-md-right">@lang('messages.Profile')</label>
						<div class="col-md-6">
							<input id="profile" type="text" class="form-control" name="profile" value="{{ $command->profile->name }}" autofocus readonly>
						</div>
					</div>
					
					<div class="form-group row">
						<label for="role" class="col-md-4 col-form-label text-md-right">@lang('messages.Role')</label>
						<div class="col-md-6">
							<input id="role" type="text" class="form-control" name="role" value="{{ $command->role->name }}" readonly>
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
