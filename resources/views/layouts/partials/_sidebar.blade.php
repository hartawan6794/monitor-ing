<div class="main-sidebar-header">
    <a href="{{ route('dashboard') }}" class="header-logo">
        {{-- Desktop logo: teks brand --}}
        @php
            $logoIcon = '<img src="'.asset('dist/assets/images/logo_dashmo.png').'" style="width:32px;height:32px;object-fit:contain;" alt="DashMo Logo">';
        @endphp

        {{-- Desktop logo (Light) --}}
        <span class="desktop-logo">
            <div style="display:flex;align-items:center;gap:8px;">
                {!! $logoIcon !!}
                <span style="font-weight:700;font-size:1rem;color:var(--header-prime-color,#1e293b);">DashMo</span>
            </div>
        </span>
        {{-- Toggle logo (Light) --}}
        <span class="toggle-logo">
            <div style="display:flex;align-items:center;justify-content:center;">
                {!! $logoIcon !!}
            </div>
        </span>
        {{-- Desktop logo (Dark) --}}
        <span class="desktop-dark">
            <div style="display:flex;align-items:center;gap:8px;">
                {!! $logoIcon !!}
                <span style="font-weight:700;font-size:1rem;color:#f1f5f9;">DashMo</span>
            </div>
        </span>
        {{-- Toggle logo (Dark) --}}
        <span class="toggle-dark">
            <div style="display:flex;align-items:center;justify-content:center;">
                {!! $logoIcon !!}
            </div>
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

            <li class="slide {{ request()->routeIs('my-subscription.*') ? 'active' : '' }}">
                <a href="{{ route('my-subscription.index') }}" class="side-menu__item">
                    <i class="bx bx-credit-card side-menu__icon"></i>
                    <span class="side-menu__label">Langganan Saya</span>
                </a>
            </li>

            @if(auth()->check() && (auth()->user()->username === 'admin' || auth()->user()->email === 'admin@gmail.com'))
            <li class="slide {{ request()->routeIs('setup.wizard') ? 'active' : '' }}">
                <a href="{{ route('setup.wizard') }}" class="side-menu__item">
                    <i class="bx bx-rocket side-menu__icon text-primary"></i>
                    <span class="side-menu__label font-bold text-primary">Setup Wizard Baru</span>
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

            <li class="slide {{ request()->routeIs('pricing_plan.*') ? 'active' : '' }}">
                <a href="{{ route('pricing_plan.index') }}" class="side-menu__item">
                    <i class="bx bx-dollar-circle side-menu__icon"></i>
                    <span class="side-menu__label">Harga & Paket</span>
                </a>
            </li>

            <li class="slide {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                <a href="{{ route('subscriptions.index') }}" class="side-menu__item">
                    <i class="bx bx-calendar-check side-menu__icon"></i>
                    <span class="side-menu__label">Langganan</span>
                </a>
            </li>

            <!-- SYSTEM -->
            <li class="slide__category"><span class="category-name">Sistem</span></li>

            <li class="slide {{ request()->routeIs('system.apk_manager') ? 'active' : '' }}">
                <a href="{{ route('system.apk_manager') }}" class="side-menu__item">
                    <i class="bx bx-mobile-alt side-menu__icon"></i>
                    <span class="side-menu__label">Upload APK</span>
                </a>
            </li>

            <li class="slide {{ request()->routeIs('system.access_keys') ? 'active' : '' }}">
                <a href="{{ route('system.access_keys') }}" class="side-menu__item">
                    <i class="bx bx-key side-menu__icon"></i>
                    <span class="side-menu__label">Access Keys</span>
                </a>
            </li>

            <li class="slide {{ request()->routeIs('system.logs') ? 'active' : '' }}">
                <a href="{{ route('system.logs') }}" class="side-menu__item">
                    <i class="bx bx-bug side-menu__icon"></i>
                    <span class="side-menu__label">System Logs</span>
                </a>
            </li>
            
            <li class="slide {{ request()->routeIs('system.email_template.*') ? 'active' : '' }}">
                <a href="{{ route('system.email_template.index') }}" class="side-menu__item">
                    <i class="bx bx-envelope side-menu__icon"></i>
                    <span class="side-menu__label">Template Email</span>
                </a>
            </li>
            @endif

        </ul>
        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                height="24" viewBox="0 0 24 24">
                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
            </svg></div>
    </nav>
    <!-- End::nav -->

</div>
<!-- End::main-sidebar -->