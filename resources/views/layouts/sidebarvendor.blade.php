<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- PROFILE --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ auth()->user()->name }}</span>
                    <span class="text-secondary text-small">Vendor</span>
                </div>
            </a>
        </li>

        {{-- MENU --}}
        <li class="nav-item {{ request()->routeIs('menu.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('menu.index') }}">
                <span class="menu-title">Menu Saya</span>
                <i class="mdi mdi-food menu-icon"></i>
            </a>
        </li>

    </ul>
</nav>