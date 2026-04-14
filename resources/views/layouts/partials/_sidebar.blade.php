<div class="main-sidebar-header">
    <a href="{{ route('dashboard') }}" class="header-logo">
        {{-- Desktop logo: teks brand --}}
        <span class="desktop-logo" style="display:flex;align-items:center;gap:8px;">
            <span
                style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#22d3ee);border-radius:9px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                    <path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z" />
                </svg>
            </span>
            <span style="font-weight:700;font-size:1rem;color:var(--header-prime-color,#1e293b);">Monitor-ing</span>
        </span>
        {{-- Toggle logo (collapsed): hanya icon --}}
        <span class="toggle-logo"
            style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#22d3ee);border-radius:9px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                <path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z" />
            </svg>
        </span>
        <span class="desktop-dark" style="display:flex;align-items:center;gap:8px;">
            <span
                style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#22d3ee);border-radius:9px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                    <path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z" />
                </svg>
            </span>
            <span style="font-weight:700;font-size:1rem;color:#f1f5f9;">Monitor-ing</span>
        </span>
        <span class="toggle-dark"
            style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#22d3ee);border-radius:9px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                <path d="M3 3h18v4H3V3zm0 6h8v4H3V9zm0 6h8v4H3v-4zm10-6h8v10h-8V9z" />
            </svg>
        </span>
    </a>
</div>
<!-- End::main-sidebar-header -->

<!-- Start::main-sidebar -->
<div class="main-sidebar" id="sidebar-scroll">

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
        <div class="slide-left" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                height="24" viewBox="0 0 24 24">
                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
            </svg></div>
        <ul class="main-menu">

            <!-- MAIN -->
            <li class="slide__category"><span class="category-name">Main</span></li>

            <li class="slide {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="side-menu__item">
                    <i class="bx bx-home-circle side-menu__icon"></i>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>

            <!-- MONITORING -->
            <li class="slide__category" style="display: none;"><span class="category-name">Monitoring</span></li>

            <li class="slide has-sub" style="display: none;">
                <a href="javascript:void(0);" class="side-menu__item">
                    <i class="bx bx-trending-up side-menu__icon"></i>
                    <span class="side-menu__label">Penjualan</span>
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide">
                        <a href="javascript:void(0);" class="side-menu__item">Riwayat Penjualan</a>
                    </li>
                    <li class="slide">
                        <a href="javascript:void(0);" class="side-menu__item">Penjualan per Salesman</a>
                    </li>
                </ul>
            </li>

            <li class="slide has-sub" style="display: none;">
                <a href="javascript:void(0);" class="side-menu__item">
                    <i class="bx bx-package side-menu__icon"></i>
                    <span class="side-menu__label">Inventori</span>
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide">
                        <a href="javascript:void(0);" class="side-menu__item">Laporan Stok</a>
                    </li>
                    <li class="slide">
                        <a href="javascript:void(0);" class="side-menu__item">Riwayat Stok</a>
                    </li>
                    <li class="slide">
                        <a href="javascript:void(0);" class="side-menu__item">Stok Masuk/Keluar</a>
                    </li>
                </ul>
            </li>

            <!-- ADMINISTRASI -->
            <li class="slide__category"><span class="category-name">Administrasi</span></li>

            <li class="slide {{ request()->routeIs('authorized_server.*') ? 'active' : '' }}">
                <a href="{{ route('authorized_server.index') }}" class="side-menu__item">
                    <i class="bx bx-server side-menu__icon"></i>
                    <span class="side-menu__label">Server Terdaftar</span>
                </a>
            </li>

            <li class="slide {{ request()->routeIs('available_database.*') ? 'active' : '' }}">
                <a href="{{ route('available_database.index') }}" class="side-menu__item">
                    <i class="bx bx-data side-menu__icon"></i>
                    <span class="side-menu__label">Database Tersedia</span>
                </a>
            </li>

            <li class="slide has-sub {{ request()->routeIs('user.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="side-menu__item">
                    <i class="bx bx-user-circle side-menu__icon"></i>
                    <span class="side-menu__label">Pengguna</span>
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide">
                        <a href="{{ route('user.index') }}" class="side-menu__item">Daftar Pengguna</a>
                    </li>
                </ul>
            </li>

        </ul>
        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                height="24" viewBox="0 0 24 24">
                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
            </svg></div>
    </nav>
    <!-- End::nav -->

</div>
<!-- End::main-sidebar -->