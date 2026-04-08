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
                    <span class="text-secondary text-small">Admin</span>
                </div>
            </a>
        </li>

        {{-- DASHBOARD --}}
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- KATEGORI --}}
        <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-shape menu-icon"></i>
            </a>
        </li>

        {{-- BUKU --}}
        <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>

        {{-- BARANG --}}
        <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Barang</span>
                <i class="mdi mdi-package menu-icon"></i>
            </a>
        </li>

        {{-- WILAYAH --}}
        <li class="nav-item {{ request()->routeIs('wilayah.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('wilayah.index') }}">
                <span class="menu-title">Wilayah</span>
                <i class="mdi mdi-city menu-icon"></i>
            </a>
        </li>

        {{-- POS --}}
        <li class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.index') }}">
                <span class="menu-title">POS</span>
                <i class="mdi mdi-cash-register menu-icon"></i>
            </a>
        </li>

        {{-- PDF --}}
        <li class="nav-item {{ request()->routeIs('pdf.sertifikat') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">
                <span class="menu-title">Sertifikat</span>
                <i class="mdi mdi-certificate menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('pdf.undangan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">
                <span class="menu-title">Undangan</span>
                <i class="mdi mdi-email menu-icon"></i>
            </a>
        </li>

    </ul>
</nav>