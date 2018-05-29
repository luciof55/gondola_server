<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
  <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <div class="d-flex" style="width: 100%">
      <!-- Left Side Of Navbar -->
      <div class="mr-auto">
        @include('layouts.main_menu')
      </div>
      <!-- Right Side Of Navbar -->
      <div class="ml-auto">
        @include('layouts.auth_links')
      </div>
    </div>
  </div>
</nav>
