<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UpSales') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
</head>
<body class="mh-100">
    <div id="app">
		<nav class="fixed-top border-bottom">
			<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
				<button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<div class="d-flex" style="width: 100%">
						<!-- Left Side Of Navbar -->
						<div class="mr-auto">
							<ul class="navbar-nav">
								<a class="navbar-brand">UpSales</a>
								@guest
								@else
									@if (Gate::allows('module', 'security'))
									<li class="nav-item active"><a class="nav-link" href="{{route('security')}}">Security</a></li>
									@endif
									@if (Gate::allows('module', 'administration'))
									<li class="nav-item active"><a class="nav-link" href="{{route('administration')}}">Administration</a></li>
									@endif
									@if (!is_null(Request::get('modulesMenuItem')))
										@foreach(Request::get('modulesMenuItem') as $menuItem)
											<li class="nav-item active"><a class="nav-link" href="{{$menuItem['url']}}">{{$menuItem['text']}}</a></li>
										@endforeach
									@endif
								 @endguest
							</ul>
						</div>
						<!-- Right Side Of Navbar -->
						<div class="ml-auto">
							<ul class="navbar-nav ml-auto">
								<!-- Authentication Links -->
								@guest
									<li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
									<li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
								@else
									<li class="nav-item dropdown">
										<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
											{{ Auth::user()->name }} <span class="caret"></span>
										</a>

										<div class="dropdown-menu" aria-labelledby="navbarDropdown">
											<a class="dropdown-item" href="{{ route('logout') }}"
											   onclick="event.preventDefault();
															 document.getElementById('logout-form').submit();">
												{{ __('Logout') }}
											</a>

											<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
												@csrf
											</form>
										</div>
									</li>
								@endguest
							</ul>
						</div>
					</div>
				</div>
			</nav>
			<div class="d-flex bg-dark">
			<div class="p-1">
				<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
					<a class="d-none d-sm-block navbar-brand" href="{{ route('home')}}"><img src="{{ asset('public/img/logo.jpg')}}" alt="Logo"></a>
					@yield('header')
				</nav>
			</div>
			<div class="p-1 ml-auto">
				<nav class="navbar bg-dark navbar-dark">
					<form class="form-inline" action="#" method="GET">
						@csrf
						<div class="input-group mb-3">
							<input type="search" class="form-control" placeholder="@lang('messages.Search')" aria-label="@lang('messages.Search')" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<button class="btn btn-success" type="submit">@lang('messages.Search')</button>
							</div>
						</div>
					</form>
				</nav>
			</div>
		</div>
        </nav>
		<div class="w-100" style="position: relative; top:130px;">
			<div class="card-body" >@yield('content')</div>
			<div class="card-footer">@yield('footer')</div>
		</div>

    </div>
	<!-- Scripts -->
	<script src="https://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="{{ asset('public/js/app.js') }}" defer></script>
</body>
</html>
