@extends('layouts.app')
@section('header')
<ul class="navbar-nav mr-auto">
	@if (Gate::allows('module', 'users'))
		@if(Request::path() == 'users')
			<li class="nav-item nav-link active">@lang('messages.Users')</li>
		@else
			<li class="nav-item "><a class="nav-link" href="{{route('security.users.index')}}">@lang('messages.Users')</a></li>
		@endif
	@endif
	@if (Gate::allows('module', 'profiles'))
		@if(Request::path() == 'profiles')
			<li class="nav-item nav-link active">@lang('messages.Profiles')</li>
		@else
			<li class="nav-item "><a class="nav-link" href="{{route('security.profiles.index')}}">@lang('messages.Profiles')</a></li>
		@endif
	@endif
	@if (Gate::allows('module', 'roles'))
		@if(Request::path() == 'roles')
			<li class="nav-item nav-link active">@lang('messages.Roles')</li>
		@else
			<li class="nav-item "><a class="nav-link" href="{{route('security.roles.index')}}">@lang('messages.Roles')</a></li>
		@endif
	@endif
	@if (Gate::allows('module', 'profilesroles'))
		@if(Request::path() == 'profilesroles')
			<li class="nav-item nav-link active">@lang('messages.ProfilesRoles')</li>
		@else
			<li class="nav-item "><a class="nav-link" href="{{route('security.profilesroles.index')}}">@lang('messages.ProfilesRoles')</a></li>
		@endif
	@endif
	@if (Gate::allows('module', 'privileges'))
		@if(Request::path() == 'privileges')
			<li class="nav-item nav-link active">@lang('messages.Privileges')</li>
		@else
			<li class="nav-item "><a class="nav-link" href="{{route('security.privileges.index')}}">@lang('messages.Privileges')</a></li>
		@endif
	@endif
	@if (Gate::allows('module', 'xxxx'))
	<li class="nav-item "><a class="nav-link" href="{{route('security.menuitems.index')}}">@lang('messages.MenuItems')</a></li>
	@endif
	@if (Gate::allows('module', 'modules'))
		@if(Request::path() == 'modules')
			<li class="nav-item nav-link active">@lang('messages.Modules')</li>
		@else
			<li class="nav-item "><a class="nav-link" href="{{route('security.modules.index')}}">@lang('messages.Modules')</a></li>
		@endif
	@endif
</ul>
@endsection
@section('content')
@yield('securityContent')
@endsection
@section('footer')
@include('footer')
@endsection
