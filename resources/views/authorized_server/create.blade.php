@extends('layouts.app')

@section('title', 'Tambah AuthorizedServer')

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3
                class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Tambah authorized_server
            </h3>
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
                    href="{{ route('authorized_server.index') }}">
                    AuthorizedServers
                    <i
                        class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50"
                aria-current="page">
                Create
            </li>
        </ol>
    </div>

    <!-- Form Tambah authorized_server -->
    <div class="box">
        <div class="box-header justify-between">
            <div class="box-title">
                Input Data authorized_server
            </div>
        </div>
        <div class="box-body">
            <form action="{{ route('authorized_server.store') }}" method="POST">
                @csrf

                <!-- Looping Dinamis untuk Field Input -->

                <div class="mb-4">
                    <label for="form-ip_address" class="form-label !text-[.875rem] text-black">
                        Ip address
                    </label>
                    <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="form-ip_address"
                        name="ip_address" value="{{ old('ip_address') }}" placeholder="Masukkan ip address">
                    @error('ip_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form-server_name" class="form-label !text-[.875rem] text-black">
                        Server name
                    </label>
                    <input type="text" class="form-control @error('server_name') is-invalid @enderror" id="form-server_name"
                        name="server_name" value="{{ old('server_name') }}" placeholder="Masukkan server name">
                    @error('server_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form-username" class="form-label !text-[.875rem] text-black">
                        Username
                    </label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="form-username"
                        name="username" value="{{ old('username') }}" placeholder="Masukkan username">
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form-password" class="form-label !text-[.875rem] text-black">
                        Password
                    </label>
                    <input type="text" class="form-control @error('password') is-invalid @enderror" id="form-password"
                        name="password" value="{{ old('password') }}" placeholder="Masukkan password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form-port" class="form-label !text-[.875rem] text-black">
                        Port
                    </label>
                    <input type="text" class="form-control @error('port') is-invalid @enderror" id="form-port" name="port"
                        value="{{ old('port') }}" placeholder="Masukkan port">
                    @error('port')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="form-is_active" class="form-label !text-[.875rem] text-black">
                        Active
                    </label>
                    <select class="form-select @error('is_active') is-invalid @enderror" id="form-is_active"
                        name="is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('is_active')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <button class="ti-btn ti-btn-primary-full" type="submit">Submit</button>
            </form>
        </div>
    </div>
@endsection