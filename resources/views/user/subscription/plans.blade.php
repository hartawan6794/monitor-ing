@extends('layouts.app')

@section('title', 'Pilih Paket Langganan — DashMo')

@section('content')
<div class="container-fluid px-4 py-6" style="max-width: 1200px; margin: 0 auto;">
    <div class="text-center mb-10">
        <a href="{{ route('my-subscription.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 dark:text-white/60 hover:text-indigo-600 dark:hover:text-indigo-400 mb-4 transition-colors">
            <i class="bx bx-arrow-back mr-1"></i> Kembali ke Langganan Saya
        </a>
        <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white mb-2">Pilih Paket Terbaik</h1>
        <p class="text-slate-500 dark:text-white/60 max-w-xl mx-auto">Sesuaikan kapasitas sistem dengan ukuran bisnis Anda. Semua paket memiliki akses ke fitur monitoring dasar.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
        @forelse($pricing_plans as $plan)
            <div class="relative flex flex-col p-8 bg-white dark:bg-bgdark border rounded-[1.5rem] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ $plan->is_featured ? 'border-indigo-500 shadow-lg shadow-indigo-500/10 dark:shadow-indigo-900/20' : 'border-slate-200 dark:border-white/10 hover:border-indigo-300 dark:hover:border-indigo-500/50' }}">
                @if($plan->is_featured)
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 px-4 py-1 bg-gradient-to-r from-indigo-500 to-cyan-400 text-white text-xs font-bold uppercase tracking-wider rounded-full shadow-md shadow-indigo-500/30">
                        {{ $plan->badge_text ?? 'Paling Populer' }}
                    </div>
                @endif
                
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ $plan->name }}</h3>
                <div class="flex items-baseline gap-1 mb-6">
                    <span class="text-3xl font-extrabold text-slate-900 dark:text-white">
                        <sup class="text-base font-bold mr-1 align-top">Rp</sup>{{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                    </span>
                    <span class="text-sm font-medium text-slate-500 dark:text-white/60">{{ $plan->price_subtext }}</span>
                </div>

                <div class="text-sm text-slate-600 dark:text-white/70 mb-6 font-medium bg-slate-50 dark:bg-black/20 px-3 py-2 rounded-lg text-center border border-slate-100 dark:border-white/5">
                    Masa Aktif: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $plan->duration_days ?? 30 }} Hari</span>
                </div>

                <ul class="space-y-4 mb-8 flex-grow">
                    @foreach($plan->features as $feature)
                        <li class="flex items-start gap-3 text-sm {{ $feature->is_highlighted ? 'text-slate-900 dark:text-white font-semibold' : 'text-slate-600 dark:text-white/70' }}">
                            <i class="bx bx-check text-xl {{ $feature->is_highlighted ? 'text-indigo-600 dark:text-indigo-400' : 'text-emerald-500 dark:text-emerald-400' }}"></i>
                            <span class="mt-0.5">{{ $feature->name }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('my-subscription.checkout', $plan->id) }}" class="w-full py-3 px-4 rounded-xl text-center font-semibold transition-all {{ $plan->is_featured ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200 dark:shadow-none' : 'bg-slate-100 dark:bg-white/5 text-slate-800 dark:text-white hover:bg-slate-200 dark:hover:bg-white/10' }}">
                    Pilih Paket Ini
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-10 text-slate-500 dark:text-white/50">
                Belum ada paket yang tersedia saat ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
