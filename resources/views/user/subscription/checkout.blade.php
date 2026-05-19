@extends('layouts.app')

@section('title', 'Checkout Langganan — DashMo')

@push('styles')
<style>
    .co-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 2rem 1rem;
        font-family: 'Inter', sans-serif;
    }
    .co-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }
    .co-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .co-desc {
        color: #64748b;
        font-size: 0.95rem;
    }
    .co-back {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: #64748b;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
    .co-back:hover {
        color: #6366f1;
    }
    
    .co-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        padding: 2.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .co-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #0ea5e9);
    }
    
    .co-order-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .co-order-title i {
        background: rgba(99,102,241,0.1);
        color: #6366f1;
        padding: 0.5rem;
        border-radius: 0.5rem;
        font-size: 1.25rem;
    }
    
    .co-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .co-item:last-child {
        border-bottom: none;
    }
    .co-item-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .co-item-desc {
        font-size: 0.85rem;
        color: #64748b;
    }
    .co-item-price {
        font-weight: 700;
        color: #0f172a;
    }
    
    .co-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
        padding: 1.25rem 1.5rem;
        border-radius: 0.75rem;
        margin-top: 1.5rem;
        border: 1px solid #f1f5f9;
    }
    .co-total-label {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }
    .co-total-val {
        font-size: 1.5rem;
        font-weight: 800;
        color: #6366f1;
    }
    
    .co-alert {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e3a8a;
        padding: 1rem;
        border-radius: 0.75rem;
        margin: 2rem 0;
        font-size: 0.9rem;
        line-height: 1.5;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }
    .co-alert i {
        color: #3b82f6;
        font-size: 1.25rem;
        margin-top: 0.1rem;
    }
    
    .btn-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        background: #6366f1;
        color: #fff;
        padding: 1.15rem;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 1.1rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);
    }
    .btn-submit:hover {
        background: #4f46e5;
        box-shadow: 0 6px 12px rgba(99, 102, 241, 0.5);
    }
    
    .co-secure {
        text-align: center;
        color: #94a3b8;
        font-size: 0.8rem;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }
    
    /* Dark Mode */
    html.dark .co-title, html.dark .co-order-title, html.dark .co-item-name, html.dark .co-item-price, html.dark .co-total-label { color: #f8fafc; }
    html.dark .co-desc, html.dark .co-back, html.dark .co-item-desc, html.dark .co-secure { color: #94a3b8; }
    html.dark .co-card { background: #1e293b; border-color: #334155; }
    html.dark .co-item { border-color: rgba(255,255,255,0.1); }
    html.dark .co-total { background: rgba(15, 23, 42, 0.4); border-color: #334155; }
    html.dark .co-total-val { color: #818cf8; }
    html.dark .co-alert { background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.2); color: #bfdbfe; }
    html.dark .co-alert i { color: #60a5fa; }
    html.dark .co-order-title i { background: rgba(99, 102, 241, 0.15); color: #818cf8; }
</style>
@endpush

@section('content')
<div class="co-container">
    <div class="co-header">
        <div>
            <h1 class="co-title">Checkout</h1>
            <p class="co-desc">Selesaikan pembayaran untuk mengaktifkan paket Anda.</p>
        </div>
        <a href="{{ route('my-subscription.plans') }}" class="co-back">
            <i class="bx bx-left-arrow-alt text-lg"></i> Batal
        </a>
    </div>

    <div class="co-card">
        <h2 class="co-order-title">
            <i class="bx bx-receipt"></i>
            <div>
                Ringkasan Pesanan
                <div style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin-top:0.2rem;">INV-{{ strtoupper(uniqid()) }}</div>
            </div>
        </h2>

        <div>
            <div class="co-item">
                <div>
                    <div class="co-item-name">{{ $plan->name }}</div>
                    <div class="co-item-desc">Masa aktif: {{ $plan->duration_days ?? 30 }} Hari</div>
                </div>
                <div class="co-item-price">
                    Rp {{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                </div>
            </div>
            <div class="co-item">
                <div class="co-item-desc" style="font-weight: 500;">Biaya Administrasi</div>
                <div class="co-item-price">Rp 0</div>
            </div>
            
            <div class="co-total">
                <div class="co-total-label">Total Pembayaran</div>
                <div class="co-total-val">
                    Rp {{ is_numeric($plan->price) ? number_format($plan->price, 0, ',', '.') : $plan->price }}
                </div>
            </div>
        </div>

        <div class="co-alert">
            <i class="bx bx-info-circle"></i>
            <div>
                <strong>Mode Simulasi Payment Gateway.</strong> 
                Saat ini pembayaran terintegrasi sedang dalam mode pengembangan (Sandbox). Klik tombol di bawah ini untuk mensimulasikan proses pembayaran yang berhasil secara sistematis.
            </div>
        </div>

        <form action="{{ route('my-subscription.processPayment', $plan->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-submit">
                <i class="bx bx-check-shield text-xl"></i>
                Simulasikan Pembayaran Berhasil
            </button>
        </form>

        <p class="co-secure">
            <i class="bx bx-lock-alt"></i> Pembayaran aman & terenkripsi
        </p>
    </div>
</div>
@endsection
