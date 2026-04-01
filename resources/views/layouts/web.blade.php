<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full" dir="ltr" data-nav-layout="horizontal" data-nav-style="menu-click" data-menu-position="fixed">
<head>
    @include('layouts.webpartials._head')
</head>
<body class="landing-body">
    <div class="landing-page-wrapper relative">
        @yield('app-header')
        @yield('app-sidebar')
        @yield('content')
    </div>
    @yield('scroll')
    <div id="responsive-overlay"></div>
@yield('javascript')
@stack('scripts')
</body>
</html>