@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">@lang('messages.User')</div>

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
							<input id="name" type="text" class="form-control" name="name" value="{{ $command->name }}" autofocus readonly>
						</div>
					</div>
					
					<div class="form-group row">
						<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
						<div class="col-md-6">
							<input id="email" type="email" class="form-control" name="email" value="{{ $command->email }}" readonly>
						</div>
					</div>
					
					<div class="form-group row">
						<label for="profile" class="col-md-4 col-form-label text-md-right">@lang('messages.Profile')</label>
						<div class="col-md-6">
							<input id="profile" type="text" class="form-control" name="profile" value="{{ $command->profile->name }}" readonly>
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
