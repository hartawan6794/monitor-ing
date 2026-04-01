<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'Login E-Office') }}</title>
<meta name="description" content="">
<meta name="keywords" content="">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('favicon.png') }}">

<!-- Main Theme Js -->
<script src="{{ asset('dist/assets/js/authentication-main.js') }}"></script>

<!-- Style Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}">

<!-- Simplebar Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/simplebar/simplebar.min.css') }}">

<!-- Color Picker Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

<!-- Simplebar Css -->
<link id="style" href="{{ asset('dist/assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

<!-- Swiper Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/swiper/swiper-bundle.min.css') }}">

{{-- @vite('resources/css/app.css') --}}