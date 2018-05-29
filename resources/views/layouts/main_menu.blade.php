<ul class="navbar-nav">
  <a class="navbar-brand d-none d-md-block">UpSales</a>
  @guest
  @else
    @if (Gate::allows('module', 'security'))
      @if(Request::path() == 'security')
        <li class="nav-item nav-link active">Security</li>
      @else
        <li class="nav-item"><a class="nav-link" href="{{route('security')}}">Security</a></li>
      @endif
      @if($submenu && ReqUtils::isCurrentMenu('security'))
        <div class="navbar-nav pl-2">@yield('header')</div>
      @endif
    @endif
    @if (Gate::allows('module', 'administration'))
      @if(Request::path() == 'administration')
        <li class="nav-item nav-link active">Administration</li>
      @else
        <li class="nav-item"><a class="nav-link" href="{{route('administration')}}">Administration</a></li>
      @endif
      @if($submenu && ReqUtils::isCurrentMenu('administration'))
        <div class="navbar-nav pl-2">@yield('header')</div>
      @endif
    @endif
    @if (!is_null(Request::get('modulesMenuItem')))
      @foreach(Request::get('modulesMenuItem') as $menuItem)
        <li class="nav-item"><a class="nav-link" href="{{$menuItem['url']}}">{{$menuItem['text']}}</a></li>
      @endforeach
    @endif
   @endguest
</ul>
