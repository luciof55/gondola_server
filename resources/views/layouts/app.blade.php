<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<!-- Left Side Of Navbar -->
				<ul class="navbar-nav mr-auto">
				  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
					<span class="navbar-toggler-icon"></span>
				  </button>
				  <div class="collapse navbar-collapse" id="navb">
					@guest
						<a class="navbar-brand">UpSales - NEW!!!!</a>
					@else
						<a class="navbar-brand">UpSales - NEW!!!!</a>
						<ul class="navbar-nav mr-auto">
							@if (Gate::allows('module', 'security'))
							<li class="nav-item active"><a class="nav-link" href="{{route('security')}}">Security</a></li>
							@endif
							@if (!is_null(Request::get('modulesMenuItem')))
								@foreach(Request::get('modulesMenuItem') as $menuItem)
									<li class="nav-item active"><a class="nav-link" href="{{$menuItem['url']}}">{{$menuItem['text']}}</a></li>
								@endforeach
							@endif
						 </ul>
					 @endguest
				  </div>
				</ul>
				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto">
					<!-- Authentication Links -->
					@guest
						<li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
						<li><a class="nav-link" href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
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
        </nav>
		<nav class="navbar justify-content-between bg-dark navbar-dark">
			<a class="navbar-brand" href="#"><img src="{{ asset('public/img/logo.jpg')}}" alt="Logo" style="width:40px;"></a>
		  <form class="form-inline my-2 my-lg-0" action="#" method="GET">
				@csrf
				<input class="form-control mr-sm-2" type="search" placeholder="@lang('messages.Search')">
				<button class="btn btn-success" type="submit">@lang('messages.Search')</button>
			</form>
		</nav>
        
		<div class="card">
			@yield('header')
			<div class="card-body">@yield('content')</div> 
			<div class="card-footer">@yield('footer')</div>
		</div>
        
    </div>
	 <!-- Scripts -->
	<script src="https://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="{{ asset('public/js/app.js') }}" defer></script>
</body>
</html>
