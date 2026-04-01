@extends('layouts.auth')

@section('content')
    <div class="flex justify-center items-center h-full">
        <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-3 sm:col-span-2"></div>
        <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-6 sm:col-span-8 col-span-12">
            <div class="my-[2.5rem] flex justify-center">
                <a href="index.html">
                    <img src="{{ asset('dist/assets/images/') }}" alt="logo" class="desktop-logo">
                </a>
            </div>
            <div class="box">
                <div class="box-body !p-[3rem]">
                    <p class="h5 font-semibold mb-2 text-center">Sign In</p>
                    <p class="mb-4 text-[#8c9097] dark:text-white/50 opacity-[0.7] font-normal text-center">Welcome back
                        Jhon !</p>
                    <form method="POST" class="xl:col-span-12 col-span-12" action="{{ route('login') }}">
                        @csrf
                        <div class="grid grid-cols-12 gap-y-4">
                            <div class="xl:col-span-12 col-span-12">
                                <label for="email" class="form-label text-default">User Name</label>
                                <input type="email" class="form-control form-control-lg w-full !rounded-md" id="email"
                                    name="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="xl:col-span-12 col-span-12 mb-2">
                                <label for="signin-password" class="form-label text-default block">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg !rounded-s-md" id="password"
                                        name="password" placeholder="password">
                                    <button aria-label="button" class="ti-btn ti-btn-light !rounded-s-none !mb-0"
                                        type="button" onclick="createpassword('password',this)"
                                        id="button-addon2"><i class="align-middle ri-eye-line"></i></button>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mt-2">
                                    <div class="form-check !ps-0">
                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                        <label class="form-check-label text-[#8c9097] dark:text-white/50 font-normal"
                                            for="defaultCheck1">
                                            Remember password ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="xl:col-span-12 col-span-12 grid mt-2">
                                <button type="submit" class="ti-btn ti-btn-primary !bg-primary !text-white !font-medium">
                                    {{ __('Login') }}</button>
                                {{-- <a href="index.html"
                                        class="ti-btn ti-btn-primary !bg-primary !text-white !font-medium">Sign In</a> --}}
                            </div>
                    </form>
                </div>
                <div class="text-center">
                    <p class="text-[0.75rem] text-[#8c9097] dark:text-white/50 mt-4">Dont have an account? <a
                            href="sign-up-basic.html" class="text-primary">Sign Up</a></p>
                </div>
                <div class="text-center my-4 authentication-barrier">
                    <span>OR</span>
                </div>
                <div class="btn-list text-center">
                    <button aria-label="button" type="button" class="ti-btn ti-btn-icon ti-btn-light me-[0.365rem]">
                        <i class="ri-facebook-line font-bold text-dark opacity-[0.7]"></i>
                    </button>
                    <button aria-label="button" type="button" class="ti-btn ti-btn-icon ti-btn-light me-[0.365rem]">
                        <i class="ri-google-line font-bold text-dark opacity-[0.7]"></i>
                    </button>
                    <button aria-label="button" type="button" class="ti-btn ti-btn-icon ti-btn-light">
                        <i class="ri-twitter-line font-bold text-dark opacity-[0.7]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="xxl:col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-3 sm:col-span-2"></div>
    </div>
@endsection
