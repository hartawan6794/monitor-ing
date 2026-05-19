@extends('layouts.app')

@section('title', 'Checkout Langganan — DashMo')

@section('content')
<div class="container-fluid px-4 py-8" style="max-width: 800px; margin: 0 auto;">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Checkout</h1>
            <p class="text-slate-500 dark:text-white/60 text-sm mt-1">Selesaikan pembayaran untuk mengaktifkan paket Anda.</p>
        </div>
        <a href="{{ route('my-subscription.plans') }}" class="text-sm font-medium text-slate-500 dark:text-white/60 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
            <i class="bx bx-left-arrow-alt align-middle text-lg mr-1"></i> Batal
        </a>
    </div>

    <div class="bg-white dark:bg-bgdark border border-slate-200 dark:border-white/10 rounded-2xl p-6 sm:p-10 shadow-sm mb-8 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/5 dark:bg-indigo-500/10 rounded-bl-full pointer-events-none"></div>
        
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-white/10 relative z-10">
            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl border border-indigo-100 dark:border-indigo-500/20">
                <i class="bx bx-receipt"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">Ringkasan Pesanan</h2>
                <p class="text-xs text-slate-500 dark:text-white/50 font-medium tracking-wider uppercase mt-0.5">INV-{{ strtoupper(uniqid()) }}</p>
            </div>
        </div>

        <div class="mb-8 relative z-10">
            <div class="flex justify-between py-4 border-b border-dashed border-slate-200 dark:border-white/10">
                <div>
                    <p class="font-semibold text-slate-800 dark:text-white">{{ $plan->name }}</p>
                    <p class="text-sm text-slate-500 dark:text-white/60 mt-1">Masa aktif: {{ $plan->duration_days ?? 30 }} Hari</p>
                </div>
                <div class="font-semibold text-slate-800 dark:text-white">
                    Rp {{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                </div>
            </div>
            <div class="flex justify-between py-4 border-b border-dashed border-slate-200 dark:border-white/10">
                <div class="text-slate-500 dark:text-white/60">Biaya Administrasi</div>
                <div class="font-semibold text-slate-800 dark:text-white">Rp 0</div>
            </div>
            <div class="flex justify-between py-5 bg-slate-50 dark:bg-black/20 -mx-6 sm:-mx-10 px-6 sm:px-10 mt-4 border-y border-slate-100 dark:border-white/5">
                <div class="font-bold text-slate-800 dark:text-white text-lg">Total Pembayaran</div>
                <div class="font-extrabold text-indigo-600 dark:text-indigo-400 text-xl">
                    Rp {{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 rounded-xl p-4 mb-8 flex gap-3 relative z-10">
            <i class="bx bx-info-circle text-blue-600 dark:text-blue-400 text-xl mt-0.5"></i>
            <div class="text-sm text-blue-800 dark:text-blue-300/80 leading-relaxed">
                <strong class="dark:text-blue-300">Mode Simulasi Payment Gateway.</strong> 
                Saat ini pembayaran terintegrasi sedang dalam mode pengembangan (Sandbox). Klik tombol di bawah ini untuk mensimulasikan proses pembayaran yang berhasil secara sistematis.
            </div>
        </div>

        <form action="{{ route('my-subscription.processPayment', $plan->id) }}" method="POST" class="relative z-10">
            @csrf
            <button type="submit" class="w-full py-4 px-6 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none dark:hover:bg-indigo-500 flex justify-center items-center gap-2 group">
                <i class="bx bx-check-shield text-xl group-hover:scale-110 transition-transform"></i>
                Simulasikan Pembayaran Berhasil
            </button>
        </form>

        <p class="text-center text-xs text-slate-400 dark:text-white/40 mt-6 flex justify-center items-center gap-1 relative z-10">
            <i class="bx bx-lock-alt"></i> Pembayaran aman & terenkripsi
        </p>
    </div>
</div>
@endsection
