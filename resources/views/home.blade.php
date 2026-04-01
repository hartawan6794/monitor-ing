@extends('layouts.web')

@section('title', 'E-Office BPKAD Provinsi Lampung')

@section('description', '')

@section('property')
@endsection

@section('app-header')
    <!-- Start::Header -->
    <header class="app-header">

        <!-- Start::main-header-container -->
        <div class="main-header-container container-fluid">

            <!-- Start::header-content-left -->
            <div class="header-content-left">

                <!-- Start::header-element -->
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a href="{{ 'route(landing)' }}" class="header-logo">
                            <img src="{{ asset('eofficeadmin/images/brand-logos/toggle-logo-eoffice.png') }}" alt="logo"
                                class="toggle-logo">
                            <img src="{{ asset('eofficeadmin/images/brand-logos/toggle-logo-eoffice.png') }}" alt="logo"
                                class="toggle-dark">
                        </a>
                    </div>
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element">
                    <!-- Start::header-link -->
                    <a aria-label="anchor" href="javascript:void(0);" class="sidemenu-toggle header-link">
                        <span class="open-toggle">
                            <i class="ri-menu-3-line text-xl"></i>
                        </span>
                    </a>
                    <!-- End::header-link -->
                </div>
                <!-- End::header-element -->

            </div>
            <!-- End::header-content-left -->

            <!-- Start::header-content-right -->
            <div class="header-content-right">

                <!-- Start::header-element -->
                <div class="header-element !items-center">
                    <!-- Start::header-link|switcher-icon -->
                    <div class="lg:hidden block">
                        <a href="{{ route('login') }}" class="ti-btn ti-btn-primary !m-0">
                            Login E-Office
                        </a>
                        {{-- <a aria-label="anchor" href="javascript:void(0);" class="ti-btn m-0 p-2 px-3 ti-btn-success"
                        data-hs-overlay="#hs-overlay-switcher"><i class="ri-settings-3-line animate-spin-slow"></i></a> --}}
                    </div>
                    <!-- End::header-link|switcher-icon -->
                </div>
                <!-- End::header-element -->

            </div>
            <!-- End::header-content-right -->

        </div>
        <!-- End::main-header-container -->

    </header>
    <!-- End::Header -->
@endsection

@section('app-sidebar')
    <!-- Start::app-sidebar -->
    <aside class="app-sidebar sticky !topacity-0" id="sidebar">
        <div class="container-xl xl:!p-0">
            <!-- Start::main-sidebar -->
            <div class="main-sidebar mx-0">
                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="landing-logo-container my-auto hidden lg:block">
                        <div class="responsive-logo">
                            <a class="responsive-logo-light" href="{{ 'route(landing)' }}" aria-label="Brand"><img
                                    src="{{ asset('eofficeadmin/images/brand-logos/eoffice-logo-dark.png') }}"
                                    alt="logo" class="mx-auto"></a>
                            <a class="responsive-logo-dark" href="{{ 'route(landing)' }}" aria-label="Brand"><img
                                    src="{{ asset('eofficeadmin/images/brand-logos/eoffice-logo.png') }}" alt="logo"
                                    class="mx-auto"></a>
                        </div>
                    </div>
                    <div class="slide-left hidden" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                            width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                        </svg></div>
                    <ul class="main-menu">
                        <!-- Start::slide -->
                        <li class="slide">
                            <a class="side-menu__item" href="#home">
                                <span class="side-menu__label">Home</span>
                            </a>
                        </li>
                        <!-- End::slide -->
                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="#statistics" class="side-menu__item">
                                <span class="side-menu__label">Statistik</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="#tentang" class="side-menu__item">
                                <span class="side-menu__label">Tentang</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="#fitur" class="side-menu__item">
                                <span class="side-menu__label">Fitur</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="#contact" class="side-menu__item">
                                <span class="side-menu__label">Kontak</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                    </ul>
                    <div class="slide-right hidden" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                            width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                        </svg></div>
                    <div class="lg:flex hidden space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('login') }}" class="ti-btn w-[15.375rem] ti-btn-primary-full m-0 p-2">Login
                            E-Office</a>
                        {{-- <a aria-label="anchor" href="javascript:void(0);" class="ti-btn m-0 p-2 px-3 ti-btn-light !font-medium"
                    data-hs-overlay="#hs-overlay-switcher"><i class="ri-settings-3-line animate-spin-slow"></i></a> --}}
                    </div>
                </nav>
                <!-- End::nav -->

            </div>
            <!-- End::main-sidebar -->
        </div>
    </aside>
    <!-- End::app-sidebar -->
@endsection

@section('content')
    <!-- Start::main-content -->
    <div class="main-content !p-0 landing-main dark:text-defaulttextcolor/70">
        <!-- Start::Home Content -->
        <div class="landing-banner" id="home">
            <section class="section">
                <div class="container main-banner-container">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="xxl:col-span-7 xl:col-span-7 lg:col-span-7 col-span-12">
                            <div class="lg:py-[3rem]">
                                <div class="mb-4">
                                    <h5 class="font-semibold text-white opacity-[0.9] text-[1.25rem]">Mengelola Arsip dengan
                                        Mudah dan Aman</h5>
                                </div>
                                <p class="landing-banner-heading mb-4 opacity-[0.9]">Transformasi Digital Pengelolaan Arsip
                                    Anda</p>
                                <div class="text-[1rem] mb-[3rem] text-white opacity-[0.9]">Nikmati kemudahan pengelolaan
                                    arsip dengan fitur pencarian canggih, keamanan data yang terjamin, dan aksesibilitas
                                    dari mana saja.</div>
                                <a href="{{ route('login') }}" class="m-1 ti-btn ti-btn-primary-full">
                                    Login ke E-Office
                                    <i class="ri-computer-line"></i>
                                </a>
                            </div>
                        </div>
                        <div class="xxl:col-span-5 xl:col-span-5 lg:col-span-5 col-span-12">
                            {{-- <div class="text-end landing-main-image landing-heading-img flex justify-end w-full"> --}}
                            <img src="{{ asset('eofficeadmin/images/media/landing/developer-team.png') }}" alt=""
                                class="img-fluid">
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- End::Home Content -->

        <!-- Start:: Section-2 -->
        <section
            class="section !bg-[#f9fafb] dark:!bg-black/10 section-bg text-defaulttextcolor dark:text-defaulttextcolor/70"
            id="statistics">
            <div class="container text-center relative">
                <p class="text-[0.75rem] font-semibold text-success mb-1"><span
                        class="landing-section-heading">STATISTIK</span></p>
                <h3 class="font-semibold mb-2 text-defaulttextcolor dark:text-defaulttextcolor/70 ">Statistik Penggunaan
                </h3>
                <div class="">
                    <div class="xl:col-span-7 col-span-12">
                        <p class="text-[#8c9097] dark:text-white/50 text-[0.9375rem] mb-12 font-normal">Lihat bagaimana
                            E-Office telah membantu banyak pengguna dalam mengelola arsip mereka dengan lebih efektif</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4">
                    <div class="col-span-1 mb-4">
                        <div
                            class="p-4 text-center !rounded-sm bg-white dark:bg-bodybg border dark:border-defaultborder/10">
                            <span class="mb-4 avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                <i class="text-[1.5rem] bx bx-buildings"></i>
                            </span>
                            <h3 class="font-semibold mb-0 text-dark">100+</h3>
                            <p class="mb-1 text-[0.875rem] opacity-[0.7] text-[#8c9097] dark:text-white/50 ">
                                Perusahaan Menggunakan E-Office
                            </p>
                        </div>
                    </div>
                    <div class="col-span-1 mb-4">
                        <div
                            class="p-4 text-center !rounded-sm !bg-white dark:!bg-bodybg border dark:border-defaultborder/10">
                            <span class="mb-4 avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                <i class="text-[1.5rem] bx bx-archive-in"></i>
                            </span>
                            <h3 class="font-semibold mb-0 text-dark">10,000+</h3>
                            <p class="mb-1 text-[0.875rem] opacity-[0.7] text-[#8c9097] dark:text-white/50 ">
                                Surat Masuk yang Diproses Setiap Bulan
                            </p>
                        </div>
                    </div>
                    <div class="col-span-1 mb-4">
                        <div
                            class="p-4 text-center !rounded-sm !bg-white dark:!bg-bodybg border dark:border-defaultborder/10">
                            <span class="mb-4 avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                <i class="text-[1.5rem] bx bx-archive-out"></i>
                            </span>
                            <h3 class="font-semibold mb-0 text-dark">8,000+</h3>
                            <p class="mb-1 text-[0.875rem] opacity-[0.7] text-[#8c9097] dark:text-white/50 ">
                                Surat Keluar yang Dikelola
                            </p>
                        </div>
                    </div>
                    <div class="col-span-1 mb-4">
                        <div
                            class="p-4 text-center !rounded-sm !bg-white dark:!bg-bodybg  border dark:border-defaultborder/10">
                            <span class="mb-4 avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                <i class="text-[1.5rem] bx bx-user"></i>
                            </span>
                            <h3 class="font-semibold mb-0 text-dark">95%</h3>
                            <p class="mb-1 text-[0.875rem] opacity-[0.7] text-[#8c9097] dark:text-white/50 ">
                                Kepuasan Pengguna
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End:: Section-2 -->

        <!-- Start:: Section-3 -->
        <section class="section text-defaulttextcolor dark:text-defaulttextcolor/70 text-[0.813rem]" id="tentang">
            <div class="container text-center">
                <p class="text-[0.75rem] font-semibold text-success mb-1"><span
                        class="landing-section-heading">TENTANG</span></p>
                <h3 class="font-semibold mb-2 dark:text-defaulttextcolor dark:text-defaulttextcolor/70710">Tentang E-Office
                </h3>
                <div class="grid grid-cols-12 justify-center max-w-xl mx-auto">
                    <div class="col-span-12">
                        <p class="text-[#8c9097] dark:text-white/50 text-[0.9375rem] mb-4 font-normal">E-Office adalah
                            aplikasi inovatif yang dirancang untuk memudahkan pengelolaan arsip surat masuk, surat keluar,
                            dan disposisi di lingkungan kantor. Dengan fitur yang lengkap dan mudah digunakan, E-Office
                            membantu meningkatkan efisiensi dan produktivitas pengelolaan arsip Anda.</p>
                    </div>
                </div>
                <div class="grid grid-cols-8 justify-center align-center">
                    <div class="xxl:col-span-3 xl:col-span-3 lg:col-span-3 col-span-12 customize-image text-center">
                        <div class="lg:text-start !flex items-center lg:justify-start justify-center">
                            <img src="{{ asset('eofficeadmin/images/media/landing/new-messages.png') }}" alt=""
                                class="img-fluid">
                        </div>
                    </div>
                    <div
                        class="xxl:col-span-5 xl:col-span-5 lg:col-span-5 col-span-12 pt-5 pb-0 lg:px-2 !text-start px-12 my-auto">
                        <h5 class="lg:text-start font-semibold mb-0 text-[1.25rem]">Arsip Digital, Solusi Cerdas</h5>
                        <p class=" text-[#8c9097] dark:text-white/50 mb-4">E-Office BPKAD menawarkan sistem manajemen arsip
                            yang menyatukan semua kebutuhan dokumen Anda dalam satu platform.</p>
                        <ul class="">
                            <li class="mb-4">
                                <div class="flex">
                                    <span>
                                        <i class="bx bxs-badge-check !text-primary text-[1.125rem]"></i>
                                    </span>
                                    <div class="ms-2">
                                        <h6 class="font-semibold mb-0 text-[1rem]">Pengelolaan Surat Masuk</h6>
                                        <p class=" text-[#8c9097] dark:text-white/50">Catat dan arsipkan setiap surat masuk
                                            dengan sistematis, sehingga memudahkan pencarian dan pengelolaan dokumen
                                            penting.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="mb-4">
                                <div class="flex">
                                    <span>
                                        <i class="bx bxs-badge-check !text-primary text-[1.125rem]"></i>
                                    </span>
                                    <div class="ms-2">
                                        <h6 class="font-semibold mb-0 text-[1rem]">Pengelolaan Surat Keluar</h6>
                                        <p class=" text-[#8c9097] dark:text-white/50">Buat, lacak, dan arsipkan surat
                                            keluar dengan mudah, memastikan setiap komunikasi tercatat dengan baik.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="">
                                <div class="flex">
                                    <span>
                                        <i class="bx bxs-badge-check !text-primary text-[1.125rem]"></i>
                                    </span>
                                    <div class="ms-2">
                                        <h6 class="font-semibold mb-0 text-[1rem]">Manajemen Disposisi</h6>
                                        <p class=" text-[#8c9097] dark:text-white/50">Distribusikan tugas dan disposisi
                                            surat dengan efisien ke setiap bagian yang terkait, memastikan alur kerja yang
                                            lancar.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- End:: Section-3 -->
        <!-- Start:: Section-4 -->
        <section
            class="section bg-[#f9fafb] section-bg text-defaulttextcolor dark:text-defaulttextcolor/70 text-[0.813rem]"
            id="fitur">
            <div class="container text-center">
                <p class="text-[0.75rem] font-semibold text-success mb-1"><span
                        class="landing-section-heading">FITUR</span></p>
                <h3 class="font-semibold mb-2">Fitur E-Office</h3>
                <div>
                    <div class="xl:col-span-7">
                        <p class="text-[#8c9097] dark:text-white/50 text-sm mb-12 font-normal">E-Office BPKAD: Pengelolaan
                            dokumen cepat, efisien, dan aman.</p>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6">
                    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-6 col-span-12">
                        <div class="box text-start landing-missions">
                            <div class="box-body">
                                <div class="items-start">
                                    <div class="mb-2">
                                        <span class="avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                            <i class="bx bx-file text-[1.5625rem]"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold mb-1 text-[1rem]">
                                            Unggah Surat Masuk
                                        </h6>
                                        <p class="mb-0 text-[#8c9097] dark:text-white/50">Mudah dan cepat dalam mengelola
                                            surat masuk. Dengan fitur unggah surat masuk, Anda dapat langsung mengunggah,
                                            menyimpan, dan mengkategorikan surat yang diterima. Sistem ini mendukung
                                            berbagai format file dan memungkinkan penambahan catatan atau tag untuk
                                            memudahkan pencarian dan referensi di masa depan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-6 col-span-12">
                        <div class="box text-start landing-missions">
                            <div class="box-body">
                                <div class="items-start">
                                    <div class="mb-2">
                                        <span class="avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                            <i class="bx bx-file text-[1.5625rem]"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold mb-1 text-[1rem]">
                                            Pengelolaan Surat Keluar
                                        </h6>
                                        <p class="mb-0 text-[#8c9097] dark:text-white/50">Pastikan surat keluar Anda
                                            terdokumentasi dengan baik. Fitur ini memungkinkan Anda membuat, mengunggah, dan
                                            menyimpan surat keluar dengan mudah. Anda dapat menambahkan informasi penerima,
                                            melacak status pengiriman, dan mengatur pengingat untuk tindak lanjut.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-6 col-span-12">
                        <div class="box text-start landing-missions">
                            <div class="box-body">
                                <div class="items-start">
                                    <div class="mb-2">
                                        <span class="avatar avatar-lg avatar-rounded  bg-primary/10 !text-primary">
                                            <i class="bx bx-copy-alt text-[1.5625rem]"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold mb-1 text-[1rem]">
                                            Disposisi Elektronik
                                        </h6>
                                        <p class="mb-0 text-[#8c9097] dark:text-white/50">Permudah alur kerja dengan fitur
                                            disposisi elektronik. Anda dapat mendistribusikan tugas dan instruksi terkait
                                            surat masuk dan surat keluar langsung melalui aplikasi. Dengan notifikasi
                                            real-time dan pelacakan status, semua pihak terkait akan selalu terinformasi dan
                                            dapat bertindak cepat.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-6 col-span-12">
                        <div class="box text-start landing-missions">
                            <div class="box-body">
                                <div class="items-start">
                                    <div class="mb-2">
                                        <span class="avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                            <i class="bx bx-printer text-[1.5625rem]"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold mb-1 text-[1rem]">
                                            Laporan Terintegrasi
                                        </h6>
                                        <p class="mb-0 text-[#8c9097] dark:text-white/50">Dapatkan wawasan mendalam dengan
                                            fitur laporan terintegrasi. Anda dapat membuat laporan kustom mengenai surat
                                            masuk, surat keluar, dan disposisi. Laporan ini dapat diunduh dalam berbagai
                                            format dan dibagikan kepada pihak terkait untuk analisis dan pengambilan
                                            keputusan yang lebih baik.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-6 col-span-12">
                        <div class="box text-start landing-missions">
                            <div class="box-body">
                                <div class="items-start">
                                    <div class="mb-2">
                                        <span class="avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                            <i class="bx bx-chalkboard text-[1.5625rem]"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold mb-1 text-[1rem]">
                                            Dashboard Manajemen Dokumen
                                        </h6>
                                        <p class="mb-0 text-[#8c9097] dark:text-white/50">Pantau semua aktivitas surat
                                            masuk, surat keluar, disposisi, dan laporan melalui dashboard yang intuitif dan
                                            user-friendly. Dashboard ini memberikan gambaran lengkap tentang status
                                            pengelolaan dokumen dan memungkinkan Anda mengambil tindakan cepat jika
                                            diperlukan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-6 col-span-12">
                        <div class="box text-start landing-missions">
                            <div class="box-body">
                                <div class="items-start">
                                    <div class="mb-2">
                                        <span class="avatar avatar-lg avatar-rounded bg-primary/10 !text-primary">
                                            <i class="bx bx-bell text-[1.5625rem]"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold mb-1 text-[1rem]">
                                            Notifikasi dan Pengingat Otomatis
                                        </h6>
                                        <p class="mb-0 text-[#8c9097] dark:text-white/50">Jangan lewatkan satupun dokumen
                                            penting dengan notifikasi dan pengingat otomatis. Sistem ini akan mengingatkan
                                            Anda tentang surat masuk yang perlu ditindaklanjuti, surat keluar yang
                                            memerlukan konfirmasi, dan disposisi yang belum selesai.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End:: Section-4 -->


        <!-- Start:: Section-10 -->
        <section class="section text-defaulttextcolor dark:text-defaulttextcolor/70 text-[0.813rem]" id="contact">
            <div class="container text-center">
                <p class="text-[0.75rem] font-semibold text-success mb-1"><span
                        class="landing-section-heading">KONTAK</span></p>
                <h3 class="font-semibold mb-2">Hubungi Kami</h3>
                <div class="grid grid-cols-12 justify-center">
                    <div class="col-span-12">
                        <p class="text-[#8c9097] dark:text-white/50 text-[0.9375rem] mb-12 font-normal">Untuk pertanyaan,
                            dukungan, atau informasi lebih lanjut mengenai E-Office BPKAD,<br> jangan ragu untuk menghubungi
                            kami. Tim kami siap membantu Anda.</p>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 text-start">
                    <div class="xxl:col-span-6 xl:col-span-6 lg:col-span-6 md:col-span-12 sm:col-spam-12 col-span-12">
                        <div class="box border dark:border-defaultborder/10 shadow-none">
                            <div class="box-body !p-0">
                                <iframe title="map"
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1985.9179430680947!2d105.258443!3d-5.441875!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40dbac2a3a2745%3A0x4184eb10d4c69557!2sBADAN%20PENGELOLAAN%20KEUANGAN%20DAN%20ASET%20DAERAH%20(BPKAD)%20PROVINSI%20LAMPUNG!5e0!3m2!1sid!2sid!4v1722265937699!5m2!1sid!2sid"
                                    height="365" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="xxl:col-span-6 xl:col-span-6 lg:col-span-6 md:col-span-12 sm:col-span-12 col-span-12">
                        <div
                            class="box  overflow-hidden !bg-[#f9fafb] dark:!bg-bodybg border dark:border-defaultborder/10 shadow-none">
                            <div class="box-body">
                                <div class="grid grid-cols-12 gap-4 mt-2 px-4">
                                    <div class="xl:col-span-6 col-span-12">
                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="xl:col-span-12 col-span-12">
                                                <label for="contact-address-name" class="form-label ">Full Name :</label>
                                                <input type="text" class="form-control w-full !rounded-md"
                                                    id="contact-address-name" placeholder="Enter Name">
                                            </div>
                                            <div class="xl:col-span-12 col-span-12">
                                                <label for="contact-address-phone" class="form-label ">Phone No :</label>
                                                <input type="text" class="form-control w-full !rounded-md"
                                                    id="contact-address-phone" placeholder="Enter Phone No">
                                            </div>
                                            <div class="xl:col-span-12 col-span-12">
                                                <label for="contact-address-address" class="form-label ">Address :</label>
                                                <textarea class="form-control w-full !rounded-md" id="contact-address-address" rows="1"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="xl:col-span-6 col-span-12">
                                        <label for="contact-address-message" class="form-label ">Message :</label>
                                        <textarea class="form-control w-full !rounded-md" id="contact-address-message" rows="8"></textarea>
                                    </div>
                                    <div class="xl:col-span-12 col-span-12">
                                        <div class="flex  mt-6 ">
                                            <div class="">
                                                <div class="btn-list">
                                                    <button aria-label="button" type="button"
                                                        class="ti-btn ti-btn-icon ti-btn-primary me-[0.375rem]">
                                                        <i class="ri-facebook-line font-bold"></i>
                                                    </button>
                                                    <button aria-label="button" type="button"
                                                        class="ti-btn ti-btn-icon ti-btn-primary me-[0.375rem]">
                                                        <i class="ri-twitter-line font-bold"></i>
                                                    </button>
                                                    <button aria-label="button" type="button"
                                                        class="ti-btn ti-btn-icon ti-btn-primary">
                                                        <i class="ri-instagram-line font-bold"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <button type="button"
                                                    class="ti-btn bg-primary  text-white !font-medium">Send
                                                    Message</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End:: Section-10 -->

        <!-- Start:: Section-11 -->
        <section class="section landing-footer  text-white text-[0.813rem] opacity-[0.87]">
            <div class="container">
                <div class="grid grid-cols-12 gap-6">
                    <div class="xl:col-span-4 col-span-12">
                        <div class="px-6">
                            <p class="font-semibold mb-4"><a aria-label="anchor" href="index.html"><img
                                        src="{{ asset('eofficeadmin/images/brand-logos/eoffice-logo.png') }}"
                                        alt=""></a></p>
                            <p class="mb-2 opacity-[0.6] font-normal">
                                Solusi digital modern untuk pengelolaan dokumen dan arsip yang efisien. E-Office BPKAD
                                mempermudah pengelolaan surat masuk, surat keluar, disposisi, dan laporan dengan teknologi
                                canggih, keamanan tinggi, dan akses yang mudah.
                            </p>
                        </div>
                    </div>
                    <div class="xl:col-span-2 col-span-12">
                        <div class="px-6">
                            <h6 class="font-semibold text-[1rem] mb-4">LINK TERKAIT</h6>
                            <ul class="list-unstyled opacity-[0.6] font-normal landing-footer-list">
                                <li>
                                    <a href="javascript:void(0);" class="text-white">SIPD</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">BPKAD</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">KEMENDAGRI</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">BANK LAMPUNG</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">SIBADIK</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">HELPDESK</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="xl:col-span-2 col-span-12">
                        <div class="px-6">
                            <h6 class="font-semibold text-[1rem] mb-2">INFO</h6>
                            <ul class="list-unstyled opacity-[0.6] font-normal landing-footer-list">
                                <li>
                                    <a href="javascript:void(0);" class="text-white">Tim Pendamping</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">Kontak Kami</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">Tentang</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">Layanan</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">Blog</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white">Terms &amp; Conditions</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="xl:col-span-4 col-span-12">
                        <div class="px-6">
                            <h6 class="font-semibold text-[1rem] mb-2">KONTAK</h6>
                            <ul class="list-unstyled font-normal landing-footer-list">
                                <li>
                                    <a href="javascript:void(0);" class="text-white opacity-[0.6]"><i
                                            class="ri-home-4-line me-1 align-middle"></i> Jl. Wolter Monginsidi No. 69.
                                        Teluk Betung Bandar Lampung kode pos 35215</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white opacity-[0.6]"><i
                                            class="ri-mail-line me-1 align-middle"></i> bpkadlampung@gmail.com</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-white opacity-[0.6]"><i
                                            class="ri-phone-line me-1 align-middle"></i> (0721) 481 166</a>
                                </li>
                                <li class="mt-4 !mb-0">
                                    <p class="mb-2 font-semibold opacity-[0.8] text-[1rem]">FOLLOW US ON :</p>
                                    <div class="mb-0">
                                        <div class="btn-list">
                                            <button aria-label="button" type="button"
                                                class="ti-btn ti-btn-sm !mb-0 ti-btn-primary me-[0.365rem]">
                                                <i class="ri-facebook-line font-bold"></i>
                                            </button>
                                            <button aria-label="button" type="button"
                                                class="ti-btn ti-btn-sm !mb-0 ti-btn-secondary me-[0.365rem]">
                                                <i class="ri-twitter-line font-bold"></i>
                                            </button>
                                            <button aria-label="button" type="button"
                                                class="ti-btn ti-btn-sm !mb-0 ti-btn-warning me-[0.365rem]">
                                                <i class="ri-instagram-line font-bold"></i>
                                            </button>
                                            <button aria-label="button" type="button"
                                                class="ti-btn ti-btn-sm !mb-0 ti-btn-success me-[0.365rem]">
                                                <i class="ri-github-line font-bold"></i>
                                            </button>
                                            <button aria-label="button" type="button"
                                                class="ti-btn ti-btn-sm !mb-0 ti-btn-danger">
                                                <i class="ri-youtube-line font-bold"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End:: Section-11 -->
        <div class="text-center landing-main-footer py-4 opacity-[0.87]">
            <span class="text-[#8c9097] dark:text-white/50 text-[0.9375rem]"> Copyright © <span id="year">2024</span>
                All
                rights
                reserved By Pusdatin BPKAD Provinsi Lampung
            </span>
        </div>
    </div>
@endsection

@section('scroll')
    <!-- Back To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill  text-[1.25rem]"></i></span>
    </div>
@endsection

@section('javascript')
    @include('layouts.webpartials._javascript')
@endsection

@push('scripts')
@endpush
