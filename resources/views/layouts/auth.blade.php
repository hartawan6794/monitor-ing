<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" class="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    
    @include('layouts.partials._head')

</head>

<body class="">
    <!-- Loader -->
    <div id="loader" >
        <img src="{{ asset('dist/assets/images/media/loader.svg') }}" alt="">
    </div>
    <!-- Loader -->

    <div class="">

        @yield('content')

    </div>

    @include('layouts.partials._jslogin')

</body>

</html>