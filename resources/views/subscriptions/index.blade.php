@extends('layouts.app')

@section('title', 'Kelola Langganan')

@section('content')
    <div class="block justify-between page-header md:flex">
        <div>
            <h3 class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                Kelola Langganan</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-[0.813rem] ps-[0.5rem]">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary truncate" href="{{ route('home') }}">
                    Dashboards
                    <i class="ti ti-chevrons-right flex-shrink-0 text-[#8c9097] px-[0.5rem] overflow-visible dark:text-white/50 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-[0.813rem] text-defaulttextcolor font-semibold hover:text-primary dark:text-[#8c9097] dark:text-white/50" aria-current="page">
                Langganan
            </li>
        </ol>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-x-12">
        <div class="xxxl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-header justify-between">
                    <div class="box-title">
                        Daftar Langganan Aktif
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ route('subscriptions.create') }}" class="ti-btn !py-1 !px-2 ti-btn-primary !font-medium !text-[0.75rem]">
                            Tambah Langganan <i class="fas fa-plus ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered min-w-full" id="subscription-table">
                            <thead>
                                <tr class="border-b border-defaultborder">
                                    <th>No</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Mulai</th>
                                    <th>Berakhir</th>
                                    <th>Sisa Hari</th>
                                    <th>Status</th>
                                    <th>Reminder</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $i => $sub)
                                    @php
                                        $daysLeft = $sub->daysUntilExpiry();
                                        $badgeClass = $sub->status !== 'active' ? 'bg-secondary/10 text-secondary' :
                                            ($daysLeft <= 1 ? 'bg-danger/10 text-danger' :
                                            ($daysLeft <= 3 ? 'bg-warning/10 text-warning' :
                                            ($daysLeft <= 7 ? 'bg-info/10 text-info' : 'bg-success/10 text-success')));
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <div class="font-semibold">{{ $sub->user->name ?? '-' }}</div>
                                            <div class="text-xs text-muted">{{ $sub->user->email ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary/10 text-primary">
                                                {{ $sub->pricingPlan->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $sub->starts_at->format('d M Y') }}</td>
                                        <td>{{ $sub->expires_at->format('d M Y') }}</td>
                                        <td>
                                            @if($sub->status === 'active')
                                                <span class="badge {{ $badgeClass }} font-bold">
                                                    {{ $daysLeft > 0 ? $daysLeft . ' hari' : 'HARI INI' }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($sub->status === 'active')
                                                <span class="badge bg-success/10 text-success">Aktif</span>
                                            @elseif($sub->status === 'expired')
                                                <span class="badge bg-danger/10 text-danger">Expired</span>
                                            @elseif($sub->status === 'cancelled')
                                                <span class="badge bg-secondary/10 text-secondary">Dibatalkan</span>
                                            @else
                                                <span class="badge bg-warning/10 text-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($sub->last_reminded_at)
                                                <span class="text-xs text-muted" title="Sudah dikirimi {{ $sub->remind_count }}x">
                                                    📩 {{ $sub->last_reminded_at->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="text-xs text-muted">Belum pernah</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                {{-- Kirim Reminder Manual --}}
                                                @if($sub->status === 'active')
                                                    <form action="{{ route('subscriptions.sendReminder', $sub->id) }}" method="POST" class="inline" onsubmit="return confirm('Kirim email reminder ke {{ $sub->user->email ?? 'pelanggan' }}?')">
                                                        @csrf
                                                        <button type="submit" class="ti-btn ti-btn-icon ti-btn-sm ti-btn-warning-full" title="Kirim Reminder">
                                                            <i class="bx bx-envelope"></i>
                                                        </button>
                                                    </form>

                                                    {{-- Perpanjang --}}
                                                    <button type="button" class="ti-btn ti-btn-icon ti-btn-sm ti-btn-success-full" title="Perpanjang"
                                                        data-bs-toggle="modal" data-bs-target="#renewModal{{ $sub->id }}">
                                                        <i class="bx bx-refresh"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Modal Perpanjang --}}
                                            @if($sub->status === 'active')
                                                <div class="hs-overlay hidden ti-modal" id="renewModal{{ $sub->id }}">
                                                    <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
                                                        <div class="ti-modal-content">
                                                            <div class="ti-modal-header">
                                                                <h6 class="modal-title">Perpanjang Langganan — {{ $sub->user->name ?? '' }}</h6>
                                                                <button type="button" class="hs-dropdown-toggle ti-modal-close-btn" data-hs-overlay="#renewModal{{ $sub->id }}">
                                                                    <span class="sr-only">Close</span><i class="bx bx-x"></i>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('subscriptions.renew', $sub->id) }}" method="POST">
                                                                @csrf
                                                                <div class="ti-modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Perpanjang Hingga Tanggal</label>
                                                                        <input type="date" name="expires_at" class="form-control"
                                                                            value="{{ $sub->expires_at->addMonth()->format('Y-m-d') }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="ti-modal-footer">
                                                                    <button type="button" class="ti-btn ti-btn-secondary-full" data-hs-overlay="#renewModal{{ $sub->id }}">Batal</button>
                                                                    <button type="submit" class="ti-btn ti-btn-primary-full">Perpanjang</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            Belum ada data langganan. <a href="{{ route('subscriptions.create') }}" class="text-primary">Tambah sekarang</a>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
