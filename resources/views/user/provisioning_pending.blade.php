@extends('layouts.app')

@section('title', 'Akun Sedang Dipersiapkan — DashMo')

@push('styles')
<style>
    .prov-wrapper {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        font-family: 'Inter', sans-serif;
    }

    .prov-card {
        max-width: 600px;
        width: 100%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.75rem;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }
    .prov-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 5px;
        background: linear-gradient(90deg, #6366f1, #22d3ee, #10b981);
        background-size: 200% 100%;
        animation: shimmer 2.5s linear infinite;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Animated gear icon */
    .prov-icon-wrap {
        width: 96px;
        height: 96px;
        background: linear-gradient(135deg, rgba(99,102,241,.12), rgba(34,211,238,.08));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        position: relative;
    }
    .prov-icon-wrap i {
        font-size: 2.75rem;
        color: #6366f1;
        animation: spin-slow 4s linear infinite;
    }
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* Pulsing ring */
    .prov-icon-wrap::after {
        content: '';
        position: absolute;
        inset: -6px;
        border-radius: 50%;
        border: 2px solid rgba(99,102,241,.2);
        animation: pulse-ring 2s ease-out infinite;
    }
    @keyframes pulse-ring {
        0%   { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.25); opacity: 0; }
    }

    .prov-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: .75rem;
        letter-spacing: -.02em;
    }
    .prov-subtitle {
        color: #64748b;
        font-size: 1rem;
        line-height: 1.7;
        max-width: 440px;
        margin: 0 auto 2rem;
    }

    /* Steps */
    .prov-steps {
        display: flex;
        flex-direction: column;
        gap: .75rem;
        text-align: left;
        margin-bottom: 2rem;
    }
    .prov-step {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        padding: .875rem 1.25rem;
    }
    .prov-step-icon {
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .prov-step-icon.done  { background: rgba(16,185,129,.12); color: #10b981; }
    .prov-step-icon.pend  { background: rgba(99,102,241,.12);  color: #6366f1; animation: blink 1.5s ease-in-out infinite; }
    .prov-step-icon.wait  { background: rgba(148,163,184,.1);  color: #94a3b8; }
    @keyframes blink {
        0%, 100% { opacity: 1; } 50% { opacity: .4; }
    }
    .prov-step-label { font-size: .9rem; font-weight: 600; color: #334155; }
    .prov-step-desc  { font-size: .78rem; color: #94a3b8; }

    .prov-eta {
        background: rgba(245,158,11,.08);
        border: 1px solid rgba(245,158,11,.2);
        border-radius: .875rem;
        padding: .875rem 1.25rem;
        font-size: .875rem;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: .6rem;
        margin-bottom: 2rem;
    }

    .btn-sub {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: #6366f1;
        color: #fff;
        padding: .8rem 1.75rem;
        border-radius: .875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        box-shadow: 0 4px 12px rgba(99,102,241,.25);
    }
    .btn-sub:hover { background: #4f46e5; color: #fff; box-shadow: 0 6px 16px rgba(99,102,241,.35); }
    .btn-logout {
        display: inline-flex; align-items: center; gap: .4rem;
        color: #94a3b8; font-size: .85rem; text-decoration: none; margin-top: 1rem; transition: color .2s;
    }
    .btn-logout:hover { color: #475569; }

    /* Dark Mode */
    html.dark .prov-card { background: #1e293b; border-color: #334155; }
    html.dark .prov-title { color: #f8fafc; }
    html.dark .prov-subtitle { color: #94a3b8; }
    html.dark .prov-step { background: rgba(15,23,42,.5); border-color: #334155; }
    html.dark .prov-step-label { color: #f1f5f9; }
    html.dark .prov-eta { background: rgba(245,158,11,.05); color: #fbbf24; border-color: rgba(245,158,11,.15); }
</style>
@endpush

@section('content')
<div class="prov-wrapper">
    <div class="prov-card">

        {{-- Animated icon --}}
        <div class="prov-icon-wrap">
            <i class="bx bx-cog"></i>
        </div>

        <h1 class="prov-title">Akun Anda Sedang Dipersiapkan</h1>
        <p class="prov-subtitle">
            Terima kasih sudah berlangganan, <strong>{{ auth()->user()->name }}</strong>!
            Tim teknis kami sedang menghubungkan database Anda. Proses ini biasanya selesai dalam waktu singkat.
        </p>

        {{-- Progress Steps --}}
        <div class="prov-steps">
            <div class="prov-step">
                <div class="prov-step-icon done"><i class="bx bx-check"></i></div>
                <div>
                    <div class="prov-step-label">Akun Terdaftar</div>
                    <div class="prov-step-desc">Nama dan email Anda sudah tersimpan di sistem.</div>
                </div>
            </div>
            <div class="prov-step">
                <div class="prov-step-icon done"><i class="bx bx-check"></i></div>
                <div>
                    <div class="prov-step-label">Pembayaran Berhasil</div>
                    @php $sub = auth()->user()->subscriptions()->where('status','active')->latest()->first(); @endphp
                    <div class="prov-step-desc">
                        Paket <strong>{{ $sub?->pricingPlan?->name ?? 'Langganan' }}</strong>
                        aktif hingga {{ $sub?->expires_at ? \Carbon\Carbon::parse($sub->expires_at)->format('d M Y') : '—' }}.
                    </div>
                </div>
            </div>
            <div class="prov-step">
                <div class="prov-step-icon pend"><i class="bx bx-loader-alt" style="animation: spin-slow 1.5s linear infinite;"></i></div>
                <div>
                    <div class="prov-step-label">Koneksi Database Sedang Disiapkan</div>
                    <div class="prov-step-desc">Tim teknis sedang menghubungkan akun Anda ke server database.</div>
                </div>
            </div>
            <div class="prov-step">
                <div class="prov-step-icon wait"><i class="bx bx-lock-open-alt"></i></div>
                <div>
                    <div class="prov-step-label">Akses Dashboard Aktif</div>
                    <div class="prov-step-desc">Anda bisa masuk begitu koneksi database selesai dikonfigurasi.</div>
                </div>
            </div>
        </div>

        {{-- ETA Notice --}}
        <div class="prov-eta">
            <i class="bx bx-time-five" style="font-size:1.25rem;flex-shrink:0;"></i>
            <div>Estimasi waktu persiapan: <strong>1 × 24 jam</strong>. Jika lebih dari itu, hubungi admin DashMo melalui WhatsApp atau email.</div>
        </div>

        <a href="{{ route('my-subscription.index') }}" class="btn-sub">
            <i class="bx bx-credit-card"></i> Lihat Detail Langganan
        </a>

        <div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-logout">
                <i class="bx bx-log-out"></i> Keluar dari Akun
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>
</div>
@endsection
