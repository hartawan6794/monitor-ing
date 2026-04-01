<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light" data-menu-styles="dark">
<head>
    @include('layouts.partials._headdashboard')
    @stack('styles')
</head>
<body>
    <!-- Loader -->
    <div id="loader" >
        <img src="{{ asset('dist/assets/images/media/loader.svg') }}" alt="">
    </div>
    <!-- Loader -->
    <div class="page">
    
    {{-- Header --}}
    <header class="app-header">
        @include('layouts.partials._header')
    </header>
    
    {{-- Sidebar --}}
    <aside class="app-sidebar" id="sidebar">
        @include('layouts.partials._sidebar')
    </aside>

    {{-- Content --}}
    <div class="content">
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-auto xl:ps-[15rem]  font-normal font-inter bg-white text-defaultsize leading-normal text-[0.813] shadow-[0_0_0.4rem_rgba(0,0,0,0.1)] dark:bg-bodybg py-4 text-center">
        <div class="container">
            @include('layouts.partials._footer')
        </div>
    </footer>
    
    @include('layouts.partials._javascript')

    @stack('scripts')
    @include('sweetalert::alert')

</body>
</html>
