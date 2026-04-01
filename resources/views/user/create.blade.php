@extends('layouts.app')

@section('title', 'Daftar User')

{{-- @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush --}}

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3
                class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Tambah Pengguna</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate"
                    href="{{ route('home') }}">
                    Dashboards
                    <i
                        class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate"
                    href="{{ route('user.index') }}">
                    Users
                    <i
                        class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50 "
                aria-current="page">
                Create
            </li>
        </ol>
    </div>

    <!-- Form Tambah Pengguna -->
    <div class="box">
        <div class="box-header justify-between">
            <div class="box-title">
                Input Data Pengguna
            </div>
        </div>
        <div class="box-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="form-text" class="form-label !text-[.875rem] text-black">Masukan nama</label>
                    <input type="text" class="form-control" id="form-text" name="name" placeholder="Enter your name">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="form-email" class="form-label !text-[.875rem] text-black">Masukan Email</label>
                    <input type="email" class="form-control" id="form-email" name="email"
                        placeholder="Enter your email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form-password" class="form-label text-[.875rem] text-black">Masukan Password</label>
                    <input type="password" class="form-control" id="form-password" name="password"
                        placeholder="Enter your password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="form-password-confirmation" class="form-label text-[.875rem] text-black">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="form-password-confirmation" name="password_confirmation" placeholder="Konfirmasi password">
                </div>

                <button class="ti-btn ti-btn-primary-full" type="submit">Submit</button>
            </form>
        </div>
    </div>



@endsection
