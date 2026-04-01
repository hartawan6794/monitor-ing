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
                Ubah Pengguna</h3>
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
                Edit
            </li>
        </ol>
    </div>

    <!-- Form Tambah Pengguna -->
    <div class="box">
        <div class="box-header justify-between">
            <div class="box-title">
                Ubah Data Pengguna
            </div>
        </div>
        <div class="box-body">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
            
                <div class="mb-4">
                    <label for="form-text" class="form-label !text-[.875rem] text-black">Enter name</label>
                    <input type="text" class="form-control" id="form-text" name="name" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="mb-4">
                    <label for="form-email" class="form-label !text-[.875rem] text-black">Enter email</label>
                    <input type="email" class="form-control" id="form-email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="mb-4">
                    <label for="form-password" class="form-label text-[.875rem] text-black">Enter New Password (optional)</label>
                    <input type="password" class="form-control" id="form-password" name="password">
                    @error('password')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="mb-4">
                    <label for="form-password-confirmation" class="form-label text-[.875rem] text-black">Confirm Password</label>
                    <input type="password" class="form-control" id="form-password-confirmation" name="password_confirmation">
                </div>
            
                <button class="ti-btn ti-btn-primary-full" type="submit">Update</button>
            </form>
        </div>
    </div>



@endsection
