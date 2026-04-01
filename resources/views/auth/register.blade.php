@extends('layouts.auth')

@section('content')
    <div class="container">
        <div
            class="flex justify-center authentication authentication-basic items-center h-full text-defaultsize text-defaulttextcolor">
            <div class="grid grid-cols-12">
                <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-3 sm:col-span-2"></div>
                <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-8 col-span-12">
                    <div class="my-[2.5rem] flex justify-center">
                        <a href="index.html">
                            <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                            <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                        </a>
                    </div>
                    <div class="box">
                        <div class="box-body !p-[3rem]">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <p class="h5 font-semibold mb-2 text-center">Sign Up</p>
                                <p class="mb-4 text-[#8c9097] dark:text-white/50 opacity-[0.7] font-normal text-center">
                                    Welcome &amp; Join us by
                                    creating a free account !</p>
                                <div class="grid grid-cols-12 gap-y-4">
                                    <div class="xl:col-span-12 col-span-12">
                                        <label for="name"
                                            class="form-label text-default">{{ __('Nama Lengkap') }}</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text"
                                                class="form-control form-control-lg w-full !rounded-md @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" required autocomplete="name"
                                                autofocus placeholder="Masukan nama">

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="xl:col-span-12 col-span-12">
                                        <label for="email" class="form-label text-default">{{ __('Email') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email"
                                                class="form-control form-control-lg w-full !rounded-md @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                                placeholder="Masukan email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="xl:col-span-12 col-span-12">
                                        <label for="password" class="form-label text-default">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <div class="input-group">

                                                <input id="password" type="password"
                                                    class="form-control form-control-lg !rounded-e-none @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="new-password"
                                                    placeholder="Masukan Password"><button aria-label="button"
                                                    type="button" class="ti-btn ti-btn-light !rounded-s-none !mb-0"
                                                    onclick="createpassword('password',this)" id="button-addon2"><i
                                                        class="ri-eye-off-line align-middle"></i></button>

                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="xl:col-span-12 col-span-12 mb-2">
                                        <label for="password_confirmation"
                                            class="form-label text-default">{{ __('Konfirmasi Password') }}</label>
                                        <div class="input-group">

                                            <input id="password_confirmation" type="password"
                                                class="form-control form-control-lg !rounded-e-none"
                                                name="password_confirmation" required autocomplete="new-password"
                                                placeholder="Konfirmasi Password">
                                            <button aria-label="button" type="button"
                                                class="ti-btn ti-btn-light !rounded-s-none !mb-0"
                                                onclick="createpassword('password_confirmation',this)"
                                                id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
                                        </div>
                                    </div>

                                </div>
                                <div class="xl:col-span-12 col-span-12 grid mt-2">
                                    <button type="submit"
                                        class="ti-btn ti-btn-lg bg-primary text-white !font-medium dark:border-defaultborder/10">
                                        {{ __('Register') }}</button>
                                </div>
                            </form>

                            <div class="text-center">
                                <p class="text-[0.75rem] text-[#8c9097] dark:text-white/50 mt-4">Already have an
                                    account? <a href="sign-in-basic.html" class="text-primary">Sign In</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-3 sm:col-span-2"></div>
            </div>
        </div>
    </div>
@endsection
