<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="@yield('title')">
<meta name="description" content="@yield('description')">

<title>@yield('title')</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

<!-- Style Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}">

<!-- Simplebar Css -->
<link id="style" href="{{ asset('dist/assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

<!-- Color Picker Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

<!-- Swiper Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/swiper/swiper-bundle.min.css') }}">

{{-- OPEN GRAPH --}}
@yield('property')