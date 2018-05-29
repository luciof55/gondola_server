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
      <div class="d-none d-md-block">@include('layouts.header', ['submenu' => false])</div>
			<div class="d-flex bg-dark">
  			<div class="p-1 w-100">
  				<nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <div class="navbar-nav d-block d-md-none w-75">@include('layouts.main_search')</div>
            <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbmenu">
              <span class="navbar-toggler-icon"></span>
            </button>
  					<a class="d-none d-md-block navbar-brand" href="{{ route('home')}}"><img src="{{ asset('public/img/logo.jpg')}}" alt="Logo"></a>
            <ul class="navbar-nav w-100">
              <div class="collapse navbar-collapse w-100" id="navbmenu">
                <div class="float-left w-50">
                  <div class="navbar-nav d-block d-md-none">@include('layouts.main_menu', ['submenu' => true])</div>
                  <div class="navbar-nav d-none d-md-block pl-2">@yield('header')</div>
                </div>
                <div class="navbar-nav d-block d-md-none float-right">@include('layouts.auth_links')</div>
                <div class="navbar-nav d-none d-md-block ml-auto">@include('layouts.main_search')</div>
              </div>
          	</ul>
  				</nav>
  			</div>
		</div>
        </nav>
		<div class="main-content w-100">
			<div class="card-body" >@yield('content')</div>
			<div class="card-footer">@yield('footer')</div>
		</div>

    </div>
	<!-- Scripts -->
	<script src="https://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="{{ asset('public/js/app.js') }}" defer></script>
</body>
</html>
