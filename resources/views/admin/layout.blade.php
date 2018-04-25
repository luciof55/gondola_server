@extends('layouts.app')

@section('header')
	<nav class="navbar navbar-expand-sm navbar-light bg-light">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSecurity" aria-controls="navbarSecurity" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSecurity">
			<ul class="navbar-nav mr-auto">
			  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbmenusec">
				<span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbmenusec">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active"><a class="nav-link" href="{{route('profiles.index')}}">@lang('messages.Profiles')</a></li>
					<li class="nav-item active"><a class="nav-link" href="{{route('roles.index')}}">@lang('messages.Roles')</a></li>
					<li class="nav-item active"><a class="nav-link" href="{{route('profilesroles.index')}}">@lang('messages.ProfilesRoles')</a></li>
					<li class="nav-item active"><a class="nav-link" href="{{route('privileges.index')}}">@lang('messages.Privileges')</a></li>
				 </ul>
			  </div>
			</ul>
		</div>
    </nav>
@endsection

@section('content')
@yield('securityContent')
@endsection

@section('footer')
<div class="row justify-content-center">
	FOOTER
</div>
@endsection