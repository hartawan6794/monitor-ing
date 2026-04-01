<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'E-Office') }} - @yield('title')</title>
<meta name="description" content="">
<meta name="keywords" content="">

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('favicon.png') }}">

<!-- Main JS -->
<script src="{{ asset('dist/assets/js/main.js') }}"></script>

{{-- @vite('resources/css/app.css') --}}

<!-- Style Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/css/style.css') }}">

<!-- Simplebar Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/simplebar/simplebar.min.css') }}">

<!-- Color Picker Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

<!-- Tabulator Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/tabulator-tables/css/tabulator.min.css') }}">

<link rel="stylesheet" href="{{ asset('dist/assets/libs/gridjs/theme/mermaid.min.css') }}">

<!-- Choices Css -->
<link rel="stylesheet" href="{{ asset('dist/assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.0/css/dataTables.dataTables.css" />

{{-- <link rel="stylesheet" href="{{ asset('eofficeadmin/css/datatablestailwindcss.css') }}"> --}}
