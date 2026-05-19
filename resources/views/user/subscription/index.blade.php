@extends('layouts.app')

@section('title', 'Langganan Saya — DashMo')

@push('styles')
<style>
    .sub-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
        font-family: 'Inter', sans-serif;
    }
    .sub-header {
        margin-bottom: 2rem;
    }
    .sub-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .sub-desc {
        color: #64748b;
        font-size: 0.95rem;
    }
    
    .sub-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 2.5rem;
    }
    .sub-card-top {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .sub-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.35rem;
    }
    .sub-val {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    .status-expired {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .sub-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .sub-grid-val {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
    }
    .sub-sisa {
        font-size: 0.8rem;
        font-weight: 600;
        color: #f59e0b;
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .btn-upgrade {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #6366f1;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-upgrade:hover {
        background: #4f46e5;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        color: #fff;
    }
    
    .table-container {
        overflow-x: auto;
    }
    .history-table {
        width: 100%;
        border-collapse: collapse;
    }
    .history-table th {
        text-align: left;
        padding: 1rem;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }
    .history-table td {
        padding: 1rem;
        color: #334155;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    .empty-icon {
        width: 4rem;
        height: 4rem;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: #94a3b8;
    }
    
    /* ── DARK MODE ── */
    html.dark .sub-title, html.dark .sub-val, html.dark .sub-grid-val { color: #f8fafc; }
    html.dark .sub-desc, html.dark .sub-label { color: #94a3b8; }
    html.dark .sub-card { background: #1e293b; border-color: #334155; }
    html.dark .sub-card-top { border-color: #334155; }
    
    html.dark .status-active { background: rgba(16, 185, 129, 0.15); color: #34d399; }
    html.dark .status-expired { background: rgba(239, 68, 68, 0.15); color: #f87171; }
    
    html.dark .history-table th { background: rgba(15, 23, 42, 0.4); border-color: #334155; color: #94a3b8; }
    html.dark .history-table td { border-color: rgba(255,255,255,0.05); color: #cbd5e1; }
    
    html.dark .alert-success { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #34d399; }
    
    html.dark .empty-icon { background: rgba(255,255,255,0.05); color: #475569; }
</style>
@endpush

@section('content')
<div class="sub-container">
    <div class="sub-header">
        <h1 class="sub-title">Langganan Saya</h1>
        <p class="sub-desc">Kelola paket langganan Anda untuk terus menikmati layanan DashMo.</p>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="bx bx-check-circle text-xl"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="sub-card">
        @if($subscription)
            <div class="sub-card-top">
                <div>
                    <div class="sub-label">Paket Saat Ini</div>
                    <div class="sub-val">{{ $subscription->pricingPlan->name ?? 'Paket Kustom' }}</div>
                </div>
                <div>
                    @if($subscription->isActive())
                        <span class="status-badge status-active"><i class="bx bx-check"></i> Aktif</span>
                    @else
                        <span class="status-badge status-expired"><i class="bx bx-x"></i> Kedaluwarsa</span>
                    @endif
                </div>
            </div>

            <div class="sub-grid">
                <div>
                    <div class="sub-label">Mulai Berlangganan</div>
                    <div class="sub-grid-val">{{ \Carbon\Carbon::parse($subscription->starts_at)->translatedFormat('d F Y') }}</div>
                </div>
                <div>
                    <div class="sub-label">Berakhir Pada</div>
                    <div class="sub-grid-val">{{ \Carbon\Carbon::parse($subscription->expires_at)->translatedFormat('d F Y') }}</div>
                    @if($subscription->isActive())
                        <div class="sub-sisa"><i class="bx bx-time"></i> Sisa {{ $subscription->daysUntilExpiry() }} hari</div>
                    @endif
                </div>
            </div>

            <div>
                <a href="{{ route('my-subscription.plans') }}" class="btn-upgrade">
                    Perpanjang / Upgrade Paket
                </a>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bx bx-package"></i>
                </div>
                <h2 class="sub-title" style="font-size:1.25rem;">Belum Ada Langganan Aktif</h2>
                <p class="sub-desc" style="max-width:400px; margin: 0.5rem auto 1.5rem;">Anda belum memiliki paket langganan yang aktif. Silakan pilih paket yang sesuai dengan kebutuhan bisnis Anda.</p>
                <a href="{{ route('my-subscription.plans') }}" class="btn-upgrade">
                    <i class="bx bx-cart"></i> Lihat Pilihan Paket
                </a>
            </div>
        @endif
    </div>

    @if($history && $history->count() > 0)
    <div class="sub-card" style="padding: 0; overflow: hidden;">
        <h3 class="sub-title" style="font-size:1.15rem; padding: 1.5rem 1.5rem 0.5rem;">Riwayat Langganan</h3>
        <div class="table-container">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Mulai</th>
                        <th>Berakhir</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $hist)
                    <tr>
                        <td style="font-weight:600;">{{ $hist->pricingPlan->name ?? 'Custom' }}</td>
                        <td>{{ \Carbon\Carbon::parse($hist->starts_at)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($hist->expires_at)->format('d M Y') }}</td>
                        <td>
                            @if($hist->status == 'active' && $hist->expires_at >= now()->toDateString())
                                <span class="status-badge status-active" style="padding:0.2rem 0.75rem; font-size:0.75rem;">Aktif</span>
                            @else
                                <span class="status-badge" style="background:#f1f5f9; color:#64748b; padding:0.2rem 0.75rem; font-size:0.75rem;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
