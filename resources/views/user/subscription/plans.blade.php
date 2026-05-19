@extends('layouts.app')

@section('title', 'Pilih Paket Langganan — DashMo')

@push('styles')
<style>
    .plans-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
        font-family: 'Inter', sans-serif;
    }
    .plans-header {
        text-align: center;
        margin-bottom: 3.5rem;
    }
    .plans-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }
    .plans-subtitle {
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        font-size: 1.05rem;
        line-height: 1.6;
    }
    
    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2.5rem;
        align-items: stretch;
    }
    
    .plan-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.5rem;
        padding: 2.5rem;
        position: relative;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }
    .plan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }
    .plan-card.featured {
        border-color: #6366f1;
        border-width: 2px;
        box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.15), 0 10px 10px -5px rgba(99, 102, 241, 0.05);
    }
    .plan-card.featured:hover {
        border-color: #4f46e5;
        box-shadow: 0 25px 30px -5px rgba(99, 102, 241, 0.25);
    }
    
    .plan-badge {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #6366f1, #0ea5e9);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.4rem 1.25rem;
        border-radius: 9999px;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }
    
    .plan-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.25rem;
    }
    
    .plan-price-wrapper {
        display: flex;
        align-items: baseline;
        gap: 0.25rem;
        margin-bottom: 1.5rem;
    }
    .plan-price {
        font-size: 2.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.03em;
    }
    .plan-price sup {
        font-size: 1.25rem;
        font-weight: 700;
        top: -1em;
        margin-right: 0.2rem;
    }
    .plan-subtext {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .plan-duration {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 0.75rem;
        padding: 0.875rem;
        text-align: center;
        font-size: 0.875rem;
        color: #475569;
        margin-bottom: 2rem;
    }
    .plan-duration strong {
        color: #6366f1;
        font-weight: 700;
        font-size: 0.95rem;
    }
    
    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem 0;
        flex-grow: 1;
    }
    .plan-features li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.15rem;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.4;
    }
    .plan-features li i {
        font-size: 1.25rem;
        color: #10b981;
        margin-top: -0.05rem;
    }
    .plan-features li.highlighted {
        font-weight: 600;
        color: #0f172a;
    }
    .plan-features li.highlighted i {
        color: #6366f1;
    }
    
    .plan-btn {
        display: block;
        width: 100%;
        text-align: center;
        padding: 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.2s;
        text-decoration: none;
    }
    .plan-btn-outline {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #e2e8f0;
    }
    .plan-btn-outline:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .plan-btn-primary {
        background: #6366f1;
        color: #fff;
        border: 1px solid #6366f1;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }
    .plan-btn-primary:hover {
        background: #4f46e5;
        color: #fff;
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.4);
    }
    
    /* ── DARK MODE ── */
    html.dark .plan-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    html.dark .plan-card:hover {
        border-color: #475569;
    }
    html.dark .plan-card.featured {
        border-color: #6366f1;
    }
    html.dark .plans-title,
    html.dark .plan-name,
    html.dark .plan-price {
        color: #f8fafc;
    }
    html.dark .plans-subtitle,
    html.dark .plan-subtext,
    html.dark .plan-features li {
        color: #94a3b8;
    }
    html.dark .plan-features li.highlighted {
        color: #f8fafc;
    }
    html.dark .plan-duration {
        background: rgba(15, 23, 42, 0.4);
        border-color: #334155;
        color: #cbd5e1;
    }
    html.dark .plan-duration strong {
        color: #818cf8;
    }
    html.dark .plan-btn-outline {
        background: #0f172a;
        border-color: #334155;
        color: #f8fafc;
    }
    html.dark .plan-btn-outline:hover {
        background: #334155;
    }
</style>
@endpush

@section('content')
<div class="plans-container">
    <div class="plans-header">
        <h1 class="plans-title">Pilih Paket Terbaik</h1>
        <p class="plans-subtitle">Sesuaikan kapasitas sistem dengan ukuran bisnis Anda. Semua paket memiliki akses ke fitur monitoring dasar.</p>
    </div>

    <div class="plans-grid">
        @forelse($pricing_plans as $plan)
            <div class="plan-card {{ $plan->is_featured ? 'featured' : '' }}">
                @if($plan->is_featured)
                    <div class="plan-badge">{{ $plan->badge_text ?? 'Paling Populer' }}</div>
                @endif
                
                <h3 class="plan-name">{{ $plan->name }}</h3>
                
                <div class="plan-price-wrapper">
                    <span class="plan-price">
                        <sup>Rp</sup>{{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                    </span>
                    <span class="plan-subtext">{{ $plan->price_subtext }}</span>
                </div>

                <div class="plan-duration">
                    Masa Aktif: <strong>{{ $plan->duration_days ?? 30 }} Hari</strong>
                </div>

                <ul class="plan-features">
                    @foreach($plan->features as $feature)
                        <li class="{{ $feature->is_highlighted ? 'highlighted' : '' }}">
                            <i class="bx bx-check"></i>
                            <span>{{ $feature->name }}</span>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('my-subscription.checkout', $plan->id) }}" class="plan-btn {{ $plan->is_featured ? 'plan-btn-primary' : 'plan-btn-outline' }}">
                    Pilih Paket Ini
                </a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #64748b;">
                Belum ada paket yang tersedia saat ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
