@extends('layouts.app')

@section('title', 'Manage Databases — ' . $server->server_name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ── BADGE HELPER ── */
    .badge-plan {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 700; letter-spacing: .04em;
    }
    .badge-active  { background: rgba(16,185,129,.12); color: #059669; }
    .badge-expired { background: rgba(239,68,68,.12);  color: #dc2626; }
    .badge-ongoing { background: rgba(99,102,241,.12); color: #6366f1; }

    /* Sub-info pill shown under user select */
    #sub-info-box {
        display: none;
        margin-top: 8px;
        background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(34,211,238,.05));
        border: 1px solid rgba(99,102,241,.2);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.82rem;
    }
    #sub-info-box.no-sub {
        background: rgba(251,191,36,.08);
        border-color: rgba(251,191,36,.25);
    }

    /* Form card animation */
    #form-card { transition: border-color .25s; }

    /* User row badge pill in select */
    .user-sub-dot {
        display: inline-block; width: 7px; height: 7px;
        border-radius: 50%; margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="block justify-between page-header md:flex">
    <div>
        <h3 class="text-[1.125rem] font-semibold text-defaulttextcolor dark:text-white">
            Manage Databases: <span class="text-primary">{{ $server->server_name }} ({{ $server->ip_address }})</span>
        </h3>
    </div>
    <ol class="flex items-center whitespace-nowrap min-w-0">
        <li class="text-[0.813rem] ps-[0.5rem]">
            <a class="text-primary hover:text-primary" href="{{ route('available_database.index') }}">Databases</a>
            <i class="ti ti-chevrons-right text-[#8c9097] px-[0.5rem]"></i>
        </li>
        <li class="text-[0.813rem] font-semibold text-defaulttextcolor dark:text-[#8c9097]" aria-current="page">Manage</li>
    </ol>
</div>

<div class="grid grid-cols-12 gap-6">
    {{-- ══════════════════════════════════════════ LEFT: DATABASE TABLE ══════════════════════════════════════════ --}}
    <div class="col-span-12 lg:col-span-7">
        <div class="box custom-box">
            <div class="box-header flex justify-between items-center">
                <div class="box-title">Database Terdaftar di Sistem</div>
                <span class="text-xs text-muted">{{ $server->availableDatabases->count() }} database</span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-full text-sm">
                        <thead>
                            <tr>
                                <th>Nama Database</th>
                                <th>Pemilik</th>
                                <th>Paket</th>
                                <th>Expired</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($server->availableDatabases as $db)
                                <tr>
                                    <td class="font-semibold text-primary">{{ $db->db_name }}</td>
                                    <td>
                                        @if($db->user)
                                            <div class="font-medium text-defaulttextcolor dark:text-white">{{ $db->user->name }}</div>
                                            <div class="text-xs text-muted">{{ $db->user->email }}</div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-plan badge-ongoing">{{ strtoupper($db->package_type ?? 'BASIC') }}</span>
                                    </td>
                                    <td class="text-xs">
                                        {{ $db->expired_at ? \Carbon\Carbon::parse($db->expired_at)->format('d M Y') : '∞ Permanent' }}
                                    </td>
                                    <td>
                                        @if(!$db->expired_at)
                                            <span class="badge-plan badge-active">Active</span>
                                        @elseif(\Carbon\Carbon::parse($db->expired_at)->isPast())
                                            <span class="badge-plan badge-expired">Expired</span>
                                        @else
                                            <span class="badge-plan badge-ongoing">Ongoing</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="ti-btn ti-btn-sm ti-btn-warning-full btn-edit-db"
                                            data-id="{{ $db->id }}" data-dbname="{{ $db->db_name }}"
                                            data-userid="{{ $db->user_id }}" data-package="{{ $db->package_type }}"
                                            data-desc="{{ $db->description }}"
                                            data-expired="{{ $db->expired_at ? \Carbon\Carbon::parse($db->expired_at)->format('Y-m-d') : '' }}"
                                            title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <form action="{{ route('available_database.destroy', $db) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger-full confirm-delete" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-muted text-sm">
                                        <i class="ri-database-2-line text-2xl block mb-2 opacity-40"></i>
                                        Belum ada database yang terdaftar di server ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Trash --}}
        <div class="box custom-box mt-4 border-t-4 border-warning">
            <div class="box-header">
                <div class="box-title text-warning"><i class="ri-delete-bin-7-line mr-1"></i> Riwayat Terhapus (Trash)</div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table w-full table-bordered text-sm">
                        <thead>
                            <tr>
                                <th>Nama Database</th>
                                <th>Tgl Dihapus</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $trashedDbs = \App\Models\AvailableDatabase::onlyTrashed()
                                    ->where('server_id', $server->id)->get();
                            @endphp
                            @forelse($trashedDbs as $trashed)
                                <tr>
                                    <td class="text-muted"><s>{{ $trashed->db_name }}</s></td>
                                    <td class="text-xs">{{ $trashed->deleted_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('available_database.restore', $trashed->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-warning-full">
                                                <i class="ri-restart-line"></i> Pulihkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-xs text-muted py-4">Tidak ada riwayat penghapusan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════ RIGHT: SMART FORM ══════════════════════════════════════════ --}}
    <div class="col-span-12 lg:col-span-5">
        <div class="box custom-box border-t-4 border-primary" id="form-card">
            <div class="box-header">
                <div class="box-title" id="form-title">
                    <i class="ri-add-circle-line mr-1"></i> Daftarkan Koneksi Baru
                </div>
            </div>
            <div class="box-body">
                <form action="{{ route('available_database.store') }}" method="POST" id="form-db">
                    @csrf
                    <div id="method-container"></div>
                    <input type="hidden" name="server_id" value="{{ $server->id }}">

                    {{-- ── Database Select ── --}}
                    <div class="mb-4">
                        <label class="form-label font-semibold text-sm">
                            <i class="ri-database-2-line mr-1 text-primary"></i> Pilih Database di Server
                        </label>
                        <div class="input-group">
                            <select class="form-control !rounded-s-md" id="db_name" name="db_name" required disabled>
                                <option value="" selected>Scan IP {{ $server->ip_address }}...</option>
                            </select>
                            <button type="button" id="btn-scan" class="ti-btn ti-btn-primary-full !rounded-e-md !mb-0">
                                <i class="ri-refresh-line"></i> Scan
                            </button>
                        </div>
                        <input type="hidden" name="db_name" id="hidden_db_name" disabled>
                    </div>

                    {{-- ── User Select ── --}}
                    <div class="mb-1">
                        <label class="form-label font-semibold text-sm">
                            <i class="ri-user-line mr-1 text-primary"></i> Pilih User Pemilik
                            <span class="text-xs font-normal text-muted ml-1">(Langganan otomatis terdeteksi)</span>
                        </label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="" disabled selected>-- Pilih User --</option>
                            @foreach($users as $user)
                                @php
                                    $userSub = $user->subscriptions->first();
                                @endphp
                                <option value="{{ $user->id }}"
                                    data-has-sub="{{ $userSub ? '1' : '0' }}"
                                    data-plan-id="{{ $userSub?->pricing_plan_id }}"
                                    data-plan-name="{{ $userSub?->pricingPlan?->name }}"
                                    data-expires="{{ $userSub?->expires_at ? \Carbon\Carbon::parse($userSub->expires_at)->format('Y-m-d') : '' }}">
                                    {{ $user->name }} ({{ $user->username ?? $user->email }})
                                    @if(!$userSub) · Belum Berlangganan @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Subscription info pill --}}
                    <div id="sub-info-box" class="mb-4">
                        <div id="sub-info-content"></div>
                    </div>

                    {{-- ── Package Type (auto-filled from subscription) ── --}}
                    <div class="mb-4">
                        <label class="form-label font-semibold text-sm">
                            <i class="ri-price-tag-3-line mr-1 text-primary"></i> Tipe Paket
                        </label>
                        <select class="form-control" id="package_type" name="package_type" required>
                            <option value="" disabled selected>-- Pilih Paket --</option>
                            @foreach($pricingPlans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                        <div id="package-info" class="text-xs text-muted mt-1 hidden">
                            <i class="ri-information-line"></i> Otomatis diisi dari langganan aktif user.
                        </div>
                    </div>

                    {{-- ── Expired At (auto-filled) ── --}}
                    <div class="mb-4">
                        <label class="form-label font-semibold text-sm">
                            <i class="ri-calendar-check-line mr-1 text-primary"></i> Masa Aktif (Expired At)
                        </label>
                        <div class="input-group">
                            <div class="input-group-text text-[#8c9097] dark:text-white/50">
                                <i class="ri-calendar-line"></i>
                            </div>
                            <input type="text" name="expired_at" id="expired_at" class="form-control flatpickr-input"
                                placeholder="Pilih Tanggal Kadaluarsa" readonly>
                        </div>
                        <div id="expiry-info" class="text-xs text-muted mt-1 hidden">
                            <i class="ri-information-line"></i> Otomatis diisi dari tanggal kedaluwarsa langganan user.
                        </div>
                        <small class="text-muted text-[10px]">*Kosongkan jika akses tidak terbatas (Permanent)</small>
                    </div>

                    {{-- ── Description ── --}}
                    <div class="mb-4">
                        <label class="form-label font-semibold text-sm">
                            <i class="ri-sticky-note-line mr-1 text-primary"></i> Deskripsi
                        </label>
                        <textarea name="description" id="description" class="form-control" rows="2"
                            placeholder="Catatan untuk database ini..."></textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="ti-btn ti-btn-success-full w-full justify-center" id="btn-submit" disabled>
                            <i class="ri-save-line mr-1"></i> <span id="submit-text">Simpan Koneksi</span>
                        </button>
                        <button type="button" class="ti-btn ti-btn-light w-full justify-center hidden" id="btn-cancel-edit">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function () {
    // ── Flatpickr ──
    const fpInstance = flatpickr("#expired_at", {
        enableTime: false,
        dateFormat: "Y-m-d",
        animate: true
    });

    // ── AJAX Scan ──
    $('#btn-scan').on('click', function () {
        let btn = $(this);
        btn.addClass('disabled').html('<i class="ri-loader-4-line animate-spin"></i> Scanning...');
        $.ajax({
            url: '/get-databases-by-server/' + "{{ $server->id }}",
            type: 'GET',
            success: function (res) {
                btn.removeClass('disabled').html('<i class="ri-refresh-line"></i> Scan');
                let dbSelect = $('#db_name').empty().prop('disabled', false);
                if (res.status === 'success' && res.data.length > 0) {
                    dbSelect.append('<option value="" disabled selected>-- Pilih Database --</option>');
                    $.each(res.data, function (k, name) {
                        dbSelect.append(`<option value="${name}">${name}</option>`);
                    });
                    $('#btn-submit').prop('disabled', false);
                } else {
                    dbSelect.append('<option disabled>Tidak ada database ditemukan</option>');
                }
            },
            error: function () {
                btn.removeClass('disabled').text('Scan Gagal');
            }
        });
    });

    // ── Auto-fill saat user dipilih ──
    $('#user_id').on('change', function () {
        const selected = $(this).find('option:selected');
        const hasSub   = selected.data('has-sub') == '1';
        const planId   = selected.data('plan-id');
        const planName = selected.data('plan-name');
        const expires  = selected.data('expires');

        const infoBox     = $('#sub-info-box');
        const infoContent = $('#sub-info-content');

        if (hasSub) {
            infoBox.removeClass('no-sub').css('display', 'block');
            infoContent.html(`
                <div class="flex items-center gap-2 flex-wrap">
                    <span style="color:#6366f1;font-weight:700;"><i class="ri-shield-check-line"></i> Langganan Aktif Terdeteksi</span>
                    <span style="background:rgba(99,102,241,.12);color:#6366f1;padding:2px 10px;border-radius:999px;font-size:.75rem;font-weight:700;">${planName ?? '—'}</span>
                    ${expires ? `<span style="color:#64748b;font-size:.78rem;"><i class="ri-calendar-event-line"></i> Exp: ${expires}</span>` : ''}
                </div>
                <div style="color:#64748b;margin-top:4px;font-size:.77rem;">Paket & tanggal kadaluarsa otomatis diisi di bawah.</div>
            `);

            // Auto-fill plan
            if (planId) {
                $('#package_type').val(planId).trigger('change');
                $('#package-info').removeClass('hidden');
            }

            // Auto-fill expired_at via flatpickr
            if (expires) {
                fpInstance.setDate(expires);
                $('#expiry-info').removeClass('hidden');
            }
        } else {
            infoBox.addClass('no-sub').css('display', 'block');
            infoContent.html(`
                <div style="color:#d97706;font-weight:600;"><i class="ri-error-warning-line"></i> User Belum Berlangganan</div>
                <div style="color:#64748b;margin-top:4px;font-size:.77rem;">Anda tetap bisa mengatur paket & expired secara manual, atau arahkan user ini untuk membeli paket terlebih dahulu.</div>
            `);
            $('#package_type').val('');
            fpInstance.clear();
            $('#package-info, #expiry-info').addClass('hidden');
        }
    });

    // ── SweetAlert delete ──
    $('.confirm-delete').on('click', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Putus Koneksi Database?',
            text: "Data fisik di MySQL tidak akan hilang, hanya akses dari sistem ini yang dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Putus!'
        }).then((r) => { if (r.isConfirmed) form.submit(); });
    });

    // ── Edit mode ──
    const storeUrl     = "{{ route('available_database.store') }}";
    const baseUpdateUrl = "{{ route('available_database.update', ':id') }}";

    $('.btn-edit-db').on('click', function () {
        let id          = $(this).data('id');
        let dbname      = $(this).data('dbname');
        let userid      = $(this).data('userid');
        let packageType = $(this).data('package');
        let desc        = $(this).data('desc');
        let expired     = $(this).data('expired');

        $('#form-title').html('<i class="ri-edit-line mr-1"></i> Edit Koneksi: ' + dbname);
        $('#form-card').removeClass('border-primary').addClass('border-warning');

        let updateUrl = baseUpdateUrl.replace(':id', id);
        $('#form-db').attr('action', updateUrl);
        $('#method-container').html('<input type="hidden" name="_method" value="PUT">');

        $('#db_name').empty().append(`<option value="${dbname}" selected>${dbname}</option>`).prop('disabled', true);
        $('#hidden_db_name').val(dbname).prop('disabled', false);
        $('#btn-scan').hide();

        if (expired) fpInstance.setDate(expired);
        else fpInstance.clear();

        $('#user_id').val(userid);
        $('#package_type').val(packageType);
        $('#description').val(desc);

        $('#submit-text').text('Update Koneksi');
        $('#btn-submit').removeClass('ti-btn-success-full').addClass('ti-btn-warning-full').prop('disabled', false);
        $('#btn-cancel-edit').removeClass('hidden');

        $('html, body').animate({ scrollTop: $("#form-card").offset().top - 80 }, 500);
    });

    // ── Cancel edit ──
    $('#btn-cancel-edit').on('click', function () {
        $('#form-title').html('<i class="ri-add-circle-line mr-1"></i> Daftarkan Koneksi Baru');
        $('#form-card').removeClass('border-warning').addClass('border-primary');
        $('#form-db').attr('action', storeUrl);
        $('#method-container').empty();

        $('#db_name').empty().append('<option value="" selected>Scan IP {{ $server->ip_address }}...</option>').prop('disabled', true);
        $('#hidden_db_name').val('').prop('disabled', true);
        $('#btn-scan').show();
        fpInstance.clear();
        $('#user_id, #package_type').val('');
        $('#description').val('');
        $('#sub-info-box').hide();
        $('#package-info, #expiry-info').addClass('hidden');

        $('#submit-text').text('Simpan Koneksi');
        $('#btn-submit').removeClass('ti-btn-warning-full').addClass('ti-btn-success-full').prop('disabled', true);
        $(this).addClass('hidden');
    });
});
</script>
@endpush