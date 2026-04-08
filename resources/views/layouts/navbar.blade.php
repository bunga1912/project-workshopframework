<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  
  {{-- LOGO --}}
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
    </a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
    </a>
  </div>

  <div class="navbar-menu-wrapper d-flex align-items-stretch">

    {{-- SIDEBAR TOGGLE --}}
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>

    {{-- SEARCH --}}
    <div class="search-field d-none d-md-block">
      <form class="d-flex align-items-center h-100">
        <div class="input-group">
          <div class="input-group-prepend bg-transparent">
            <i class="input-group-text border-0 mdi mdi-magnify"></i>
          </div>
          <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
        </div>
      </form>
    </div>

    <ul class="navbar-nav navbar-nav-right">

      {{-- 🔥 TOMBOL LOGIN ADMIN/VENDOR --}}
      @guest
      <li class="nav-item d-flex align-items-center mr-3">
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
          Login Admin / Vendor
        </a>
      </li>
      @endguest


      {{-- PROFILE --}}
      <li class="nav-item nav-profile dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
          <div class="nav-profile-img">
            <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image">
            <span class="availability-status online"></span>
          </div>

          <div class="nav-profile-text">
            @if(auth()->check())
                <p class="mb-1 text-black">{{ auth()->user()->name }}</p>
            @else
                <p class="mb-1 text-black">Customer</p>
            @endif
          </div>
        </a>

        {{-- DROPDOWN --}}
        <div class="dropdown-menu navbar-dropdown">

          @auth
              <a href="{{ route('dashboard') }}" class="dropdown-item">
                  <i class="mdi mdi-view-dashboard mr-2 text-primary"></i> Dashboard
              </a>

              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item">
                      <i class="mdi mdi-logout mr-2 text-primary"></i> Logout
                  </button>
              </form>
          @endauth

          @guest
              <a href="{{ route('login') }}" class="dropdown-item">
                  <i class="mdi mdi-login mr-2 text-primary"></i> Login
              </a>
          @endguest

        </div>
      </li>

      {{-- FULLSCREEN --}}
      <li class="nav-item d-none d-lg-block">
        <a class="nav-link" href="#" id="fullscreen-button">
          <i class="mdi mdi-fullscreen"></i>
        </a>
      </li>

      {{-- MESSAGE --}}
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-email-outline"></i>
          <span class="count-symbol bg-warning"></span>
        </a>
      </li>

      {{-- NOTIFICATION --}}
      <li class="nav-item dropdown">
        <a class="nav-link count-indicator dropdown-toggle" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
          <span class="count-symbol bg-danger"></span>
        </a>
      </li>

      {{-- POWER --}}
      <li class="nav-item d-none d-lg-block">
        <a class="nav-link" href="#">
          <i class="mdi mdi-power"></i>
        </a>
      </li>

      {{-- RIGHT SIDEBAR --}}
      <li class="nav-item nav-settings d-none d-lg-block">
        <a class="nav-link" href="#">
          <i class="mdi mdi-format-line-spacing"></i>
        </a>
      </li>

    </ul>
  </div>
</nav>