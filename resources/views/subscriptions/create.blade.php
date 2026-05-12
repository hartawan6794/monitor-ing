@extends('layouts.app')

@section('title', 'Tambah Langganan')

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3 class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Tambah Langganan Baru</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate" href="{{ route('subscriptions.index') }}">
                    Langganan
                    <i class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50" aria-current="page">
                Tambah Baru
            </li>
        </ol>
    </div>

    <div class="grid grid-cols-12 gap-x-12">
        <div class="xxxl:col-span-8 col-span-12 xl:col-span-8">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="box-title">Form Langganan</div>
                </div>
                <div class="box-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('subscriptions.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-12 gap-4">
                            {{-- Pelanggan --}}
                            <div class="xl:col-span-6 col-span-12">
                                <label for="user_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">— Pilih Pelanggan —</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Paket --}}
                            <div class="xl:col-span-6 col-span-12">
                                <label for="pricing_plan_id" class="form-label">Paket Harga <span class="text-danger">*</span></label>
                                <select name="pricing_plan_id" id="pricing_plan_id" class="form-control" required>
                                    <option value="">— Pilih Paket —</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('pricing_plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} — {{ $plan->price }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div class="xl:col-span-6 col-span-12">
                                <label for="starts_at" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="starts_at" id="starts_at" class="form-control"
                                    value="{{ old('starts_at', date('Y-m-d')) }}" required>
                            </div>

                            {{-- Tanggal Berakhir --}}
                            <div class="xl:col-span-6 col-span-12">
                                <label for="expires_at" class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                                <input type="date" name="expires_at" id="expires_at" class="form-control"
                                    value="{{ old('expires_at', date('Y-m-d', strtotime('+1 month'))) }}" required>
                            </div>

                            {{-- Catatan --}}
                            <div class="col-span-12">
                                <label for="notes" class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Tambahkan catatan internal...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <a href="{{ route('subscriptions.index') }}" class="ti-btn ti-btn-secondary-full">Batal</a>
                            <button type="submit" class="ti-btn ti-btn-primary-full">
                                <i class="bx bx-save me-1"></i> Simpan Langganan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
