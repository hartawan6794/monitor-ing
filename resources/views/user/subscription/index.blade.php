@extends('layouts.app')

@section('title', 'Langganan Saya — DashMo')

@section('content')
<div class="container-fluid px-4 py-6" style="max-width: 1000px; margin: 0 auto;">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Langganan Saya</h1>
        <p class="text-slate-500 dark:text-white/60 text-sm mt-1">Kelola paket langganan Anda untuk terus menikmati layanan DashMo.</p>
    </div>

    @if(session('success'))
    <div class="p-4 mb-6 text-sm text-emerald-800 dark:text-emerald-400 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
        <div class="flex items-center gap-2">
            <i class="bx bx-check-circle text-xl"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-bgdark border border-slate-200 dark:border-white/10 rounded-2xl p-6 md:p-8 shadow-sm mb-8">
        @if($subscription)
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-8 border-b border-slate-100 dark:border-white/10">
                <div>
                    <h2 class="text-lg text-slate-500 dark:text-white/60 mb-1">Paket Saat Ini</h2>
                    <div class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $subscription->pricingPlan->name ?? 'Paket Kustom' }}</div>
                </div>
                <div>
                    @if($subscription->isActive())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100/50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400"><i class="bx bx-check mr-1"></i> Aktif</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100/50 text-red-600 dark:bg-red-500/10 dark:text-red-400"><i class="bx bx-x mr-1"></i> Kedaluwarsa</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-white/60 mb-1">Mulai Berlangganan</p>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($subscription->starts_at)->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-white/60 mb-1">Berakhir Pada</p>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($subscription->expires_at)->translatedFormat('d F Y') }}</p>
                    @if($subscription->isActive())
                        <p class="text-xs text-orange-500 dark:text-orange-400 mt-1 font-medium"><i class="bx bx-time"></i> Sisa {{ $subscription->daysUntilExpiry() }} hari</p>
                    @endif
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('my-subscription.plans') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 dark:shadow-none">
                    Perpanjang / Upgrade Paket
                </a>
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-20 h-20 bg-slate-100 dark:bg-black/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200 dark:border-white/5">
                    <i class="bx bx-package text-4xl text-slate-400 dark:text-white/40"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum Ada Langganan Aktif</h2>
                <p class="text-slate-500 dark:text-white/60 mb-6 max-w-md mx-auto">Anda belum memiliki paket langganan yang aktif. Silakan pilih paket yang sesuai dengan kebutuhan bisnis Anda.</p>
                <a href="{{ route('my-subscription.plans') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 dark:shadow-none">
                    <i class="bx bx-cart mr-2"></i> Lihat Pilihan Paket
                </a>
            </div>
        @endif
    </div>

    @if($history && $history->count() > 0)
    <div class="mt-8">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Riwayat Langganan</h3>
        <div class="bg-white dark:bg-bgdark border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-black/20 text-slate-500 dark:text-white/60 text-sm border-b border-slate-200 dark:border-white/10">
                            <th class="py-4 px-6 font-semibold">Paket</th>
                            <th class="py-4 px-6 font-semibold">Mulai</th>
                            <th class="py-4 px-6 font-semibold">Berakhir</th>
                            <th class="py-4 px-6 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($history as $hist)
                        <tr class="hover:bg-slate-50 dark:hover:bg-black/10 transition-colors">
                            <td class="py-4 px-6 font-medium text-slate-800 dark:text-white">{{ $hist->pricingPlan->name ?? 'Custom' }}</td>
                            <td class="py-4 px-6 text-slate-600 dark:text-white/70">{{ \Carbon\Carbon::parse($hist->starts_at)->format('d M Y') }}</td>
                            <td class="py-4 px-6 text-slate-600 dark:text-white/70">{{ \Carbon\Carbon::parse($hist->expires_at)->format('d M Y') }}</td>
                            <td class="py-4 px-6">
                                @if($hist->status == 'active' && $hist->expires_at >= now()->toDateString())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-500 dark:bg-white/5 dark:text-white/50 border border-slate-200 dark:border-white/10">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
