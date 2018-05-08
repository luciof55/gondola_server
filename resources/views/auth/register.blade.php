@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@lang('messages.User')</div>

                <div class="card-body">
                    <form id="commandForm" method="POST" action="{{ route('register') }}">
                        @csrf
						<input id="id" name="id" type="hidden" value="{{ $command->id }}">
						<input type="hidden" id="_method" name="_method" value="{{ $method }}">
						<input type="hidden" id="page" name="page" value="{{ $page }}">
						@foreach ($filters->keys() as $filterKey)
							<input type="hidden" name="{{$filterKey}}" value="{{ $filters->get($filterKey) }}">
						@endforeach
						@if (isset($command->profile))
							<?php $profile_id = $command->profile->id; ?>
						@else
							<?php $profile_id = null; ?>
						@endif
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
						
						@if (Auth::check() && Gate::allows('create', 'users'))
							<div class="form-group row">
								
								<label for="profile_id" class="col-md-4 col-form-label text-md-right">@lang('messages.Profile')</label>
								<div class="col-md-6">
									@if ($errors->has('profile_id'))
										{{ Form::select('profile_id', $profiles, $profile_id, ['required', 'autofocus', 'placeholder' => 'Pick a profile...', 'class' => 'form-control is-invalid'])}}
									@else
										{{ Form::select('profile_id', $profiles, $profile_id, ['required', 'autofocus', 'placeholder' => 'Pick a profile...', 'class' => 'form-control'])}}
									@endif
									@if ($errors->has('profile_id'))
										<span class="invalid-feedback">
											<strong>{{ $errors->first('profile_id') }}</strong>
										</span>
									@endif
								</div>
							</div>
						@endif
						
                        <div class="form-group row mb-0">
                            @include('admin.down_buttons', ['btn_save' => true])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
