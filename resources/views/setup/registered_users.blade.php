@extends('layouts.app')

@section('title', 'User Terdaftar — DashMo Admin')

@push('styles')
<style>
    /* ── STATS CARDS ── */
    .reg-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }
    .reg-stat-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
        padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem;
        position: relative; overflow: hidden; transition: box-shadow .2s, transform .2s;
    }
    .reg-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.07); }
    .reg-stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:16px 16px 0 0; }
    .reg-stat-card.total::before  { background: linear-gradient(90deg,#6366f1,#22d3ee); }
    .reg-stat-card.subbed::before { background: linear-gradient(90deg,#10b981,#059669); }
    .reg-stat-card.nosub::before  { background: linear-gradient(90deg,#f59e0b,#ef4444); }
    .reg-stat-card.pend::before   { background: linear-gradient(90deg,#6366f1,#818cf8); }
    .reg-stat-icon { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.35rem;flex-shrink:0; }
    .reg-stat-icon.indigo  { background:rgba(99,102,241,.1); color:#6366f1; }
    .reg-stat-icon.emerald { background:rgba(16,185,129,.1); color:#10b981; }
    .reg-stat-icon.amber   { background:rgba(245,158,11,.1); color:#f59e0b; }
    .reg-stat-val   { font-size:1.75rem; font-weight:800; color:#0f172a; letter-spacing:-.03em; line-height:1; }
    .reg-stat-label { font-size:.8rem; color:#64748b; margin-top:.2rem; font-weight:500; }

    /* ── FILTER BAR ── */
    .filter-bar { display:flex; flex-wrap:wrap; gap:.75rem; align-items:center; margin-bottom:1.25rem; }
    .filter-pill {
        display:inline-flex; align-items:center; gap:.4rem;
        padding:.35rem .9rem; border-radius:999px; font-size:.8rem; font-weight:600;
        cursor:pointer; border:1.5px solid transparent; transition:all .2s;
        background:#f1f5f9; color:#475569;
    }
    .filter-pill.active, .filter-pill:hover   { border-color:#6366f1; color:#6366f1; background:rgba(99,102,241,.08); }
    .filter-pill.green.active, .filter-pill.green:hover   { border-color:#10b981; color:#10b981; background:rgba(16,185,129,.08); }
    .filter-pill.amber.active, .filter-pill.amber:hover   { border-color:#f59e0b; color:#d97706; background:rgba(245,158,11,.08); }
    .filter-pill.indigo.active, .filter-pill.indigo:hover { border-color:#6366f1; color:#6366f1; background:rgba(99,102,241,.08); }

    /* ── TABLE WRAPPER (scroll fix) ── */
    .reg-table-wrap {
        background:#fff; border:1px solid #e2e8f0; border-radius:16px;
        overflow: hidden; /* outer radius */
        box-shadow:0 4px 6px -1px rgba(0,0,0,.04);
    }
    .reg-table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    /* DataTables overrides */
    #usersTable { width:100% !important; border-collapse:collapse; }
    #usersTable thead th {
        background:#f8fafc; padding:.9rem 1.25rem;
        font-size:.78rem; font-weight:700; color:#64748b;
        text-transform:uppercase; letter-spacing:.04em;
        border-bottom:1px solid #e2e8f0; white-space:nowrap;
    }
    #usersTable tbody td {
        padding:.85rem 1.25rem; font-size:.875rem; color:#334155;
        border-bottom:1px solid #f1f5f9; vertical-align:middle;
    }
    #usersTable tbody tr:last-child td { border-bottom:none; }
    #usersTable tbody tr:hover td { background:#fafbff; }

    /* DT controls */
    .dataTables_wrapper .dataTables_paginate { padding: 1rem 1.25rem; }
    .dataTables_wrapper .dataTables_info     { padding: 1rem 1.25rem; font-size:.82rem; color:#64748b; }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { display:none; } /* hidden — kita pakai custom */

    /* Pagination styling */
    .dataTables_wrapper .paginate_button {
        padding:.35rem .7rem !important; border-radius:8px !important; font-size:.82rem !important;
        border:1.5px solid #e2e8f0 !important; margin:0 2px !important; cursor:pointer;
    }
    .dataTables_wrapper .paginate_button.current, .dataTables_wrapper .paginate_button.current:hover {
        background:#6366f1 !important; color:#fff !important; border-color:#6366f1 !important;
    }
    .dataTables_wrapper .paginate_button:hover { border-color:#6366f1 !important; color:#6366f1 !important; background:rgba(99,102,241,.08) !important; }
    .dataTables_wrapper .paginate_button.disabled, .dataTables_wrapper .paginate_button.disabled:hover { color:#94a3b8 !important; border-color:#f1f5f9 !important; background:transparent !important; }

    /* ── STATUS BADGES ── */
    .badge-sub { display:inline-flex;align-items:center;gap:.3rem;padding:.25rem .75rem;border-radius:999px;font-size:.75rem;font-weight:700; }
    .badge-active  { background:rgba(16,185,129,.12); color:#059669; }
    .badge-expired { background:rgba(239,68,68,.12);  color:#dc2626; }
    .badge-nosub   { background:rgba(245,158,11,.12); color:#d97706; }
    .badge-pending { background:rgba(99,102,241,.12); color:#6366f1; }
    .badge-db      { background:rgba(99,102,241,.1);  color:#6366f1; font-size:.7rem; padding:.15rem .55rem; border-radius:6px; }
    @keyframes blink-badge { 0%,100%{opacity:1} 50%{opacity:.45} }

    /* DARK MODE */
    html.dark .reg-stat-card  { background:#1e293b; border-color:#334155; }
    html.dark .reg-stat-val   { color:#f8fafc; }
    html.dark .reg-stat-label { color:#94a3b8; }
    html.dark .filter-pill    { background:#1e293b; color:#94a3b8; border-color:#334155; }
    html.dark .reg-table-wrap { background:#1e293b; border-color:#334155; }
    html.dark #usersTable thead th { background:rgba(15,23,42,.5); border-color:#334155; }
    html.dark #usersTable tbody td { color:#cbd5e1; border-color:rgba(255,255,255,.05); }
    html.dark #usersTable tbody tr:hover td { background:rgba(99,102,241,.05); }
    html.dark .prov-dropdown  { background:#1e293b !important; border-color:#334155 !important; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="block justify-between page-header md:flex mb-4">
    <div>
        <h3 class="text-[1.25rem] font-bold text-defaulttextcolor dark:text-white">User Terdaftar</h3>
        <p class="text-[0.85rem] text-muted mt-0.5">Daftar seluruh user yang telah mendaftar beserta status langganan mereka.</p>
    </div>
    <div class="flex items-center gap-3 mt-2 md:mt-0">
        <a href="{{ route('setup.wizard') }}" class="ti-btn ti-btn-primary-full flex items-center gap-2 !py-2 !px-4 !rounded-xl !text-sm">
            <i class="ri-user-add-line"></i> Setup User Baru
        </a>
    </div>
</div>

{{-- STATS --}}
<div class="reg-stats">
    <div class="reg-stat-card total">
        <div class="reg-stat-icon indigo"><i class="ri-group-line"></i></div>
        <div><div class="reg-stat-val">{{ $totalUsers }}</div><div class="reg-stat-label">Total User Terdaftar</div></div>
    </div>
    <div class="reg-stat-card subbed">
        <div class="reg-stat-icon emerald"><i class="ri-shield-check-line"></i></div>
        <div><div class="reg-stat-val">{{ $subscribedUsers }}</div><div class="reg-stat-label">Berlangganan Aktif</div></div>
    </div>
    <div class="reg-stat-card pend">
        <div class="reg-stat-icon indigo"><i class="ri-loader-3-line"></i></div>
        <div><div class="reg-stat-val">{{ $pendingUsers }}</div><div class="reg-stat-label">Menunggu Koneksi DB</div></div>
    </div>
    <div class="reg-stat-card nosub">
        <div class="reg-stat-icon amber"><i class="ri-alarm-warning-line"></i></div>
        <div><div class="reg-stat-val">{{ $noSubUsers }}</div><div class="reg-stat-label">Belum Berlangganan</div></div>
    </div>
</div>

{{-- FILTER + SEARCH --}}
<div class="filter-bar">
    <button class="filter-pill active" data-prov="all"><i class="ri-list-unordered"></i> Semua</button>
    <button class="filter-pill green"  data-prov="provisioned"><i class="ri-shield-check-line"></i> Aktif & Terhubung</button>
    <button class="filter-pill indigo" data-prov="pending"><i class="ri-loader-3-line"></i> Menunggu Koneksi DB</button>
    <button class="filter-pill amber"  data-prov="unregistered"><i class="ri-alarm-warning-line"></i> Belum Berlangganan</button>

    <div class="ms-auto flex items-center gap-2">
        {{-- Per-page selector --}}
        <select id="perPageSelect"
            style="padding:.4rem .75rem;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.85rem;outline:none;cursor:pointer;transition:border .2s;"
            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
            <option value="10">10 / hal</option>
            <option value="25" selected>25 / hal</option>
            <option value="50">50 / hal</option>
            <option value="100">100 / hal</option>
        </select>
        {{-- Search --}}
        <div style="position:relative;">
            <i class="ri-search-line" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
            <input type="text" id="dtSearch" placeholder="Cari nama / email..."
                style="padding:.45rem .75rem .45rem 2.25rem;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.85rem;width:220px;outline:none;transition:border .2s;"
                onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
        </div>
    </div>
</div>

{{-- TABLE --}}
<div class="reg-table-wrap">
    <div class="reg-table-scroll">
        <table id="usersTable" class="display nowrap">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th style="width:40px;"></th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Status Langganan</th>
                    <th>Paket Aktif</th>
                    <th>Exp. Date</th>
                    <th>DB Terhubung</th>
                    <th class="text-center" style="min-width:140px;">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="flex items-center justify-between px-5 py-3 border-t border-gray-100 dark:border-slate-700 flex-wrap gap-2">
        <div id="dtInfo" class="text-xs text-muted"></div>
        <div id="dtPaginate"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    const AJAX_URL  = '{{ route("registered.users.data") }}';
    const CSRF      = '{{ csrf_token() }}';
    let   provFilter = 'all';

    // ── DataTable init ──
    const table = $('#usersTable').DataTable({
        processing:  true,
        serverSide:  true,
        scrollX:     true,   // horizontal scroll if needed
        dom:         'rt',   // only render table + processing; we control everything else
        pageLength:  25,
        ajax: {
            url: AJAX_URL,
            data: function (d) {
                d.prov_status = provFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'id', orderable: false, searchable: false, className: 'text-muted text-xs' },
            { data: 'avatar',      name: 'avatar', orderable: false, searchable: false },
            { data: 'user_info',   name: 'name' },
            { data: 'email',       name: 'email', className: 'text-muted' },
            { data: 'status_badge', name: 'provisioning_status', orderable: false },
            { data: 'plan_name',   name: 'plan_name', orderable: false },
            { data: 'expiry',      name: 'expiry', orderable: false },
            { data: 'db_list',     name: 'db_list', orderable: false, searchable: false },
            { data: 'actions',     name: 'actions', orderable: false, searchable: false, className: 'text-center' },
        ],
        language: {
            processing:  '<div style="padding:1rem;color:#6366f1;"><i class="ri-loader-3-line" style="font-size:1.4rem;animation:spin-dt .8s linear infinite;display:inline-block;"></i> Memuat data...</div>',
            emptyTable:  '<div style="padding:2rem;text-align:center;color:#94a3b8;">Tidak ada data yang ditemukan.</div>',
            zeroRecords: '<div style="padding:2rem;text-align:center;color:#94a3b8;">Tidak ada hasil yang cocok.</div>',
        },
        drawCallback: function (settings) {
            const api  = this.api();
            const info = api.page.info();

            // Custom info
            const start = info.start + 1, end = info.end, total = info.recordsTotal;
            $('#dtInfo').html(`Menampilkan <b>${start}–${end}</b> dari <b>${total}</b> user`);

            // Move paginate into custom container
            $('#dtPaginate').html($('#usersTable_paginate').html());

            // Re-bind dropdown handlers after each draw
            bindDropdowns();
        },
        initComplete: function () {
            // Move built-in paginate out so we can hide it but keep its events
            $('#usersTable_paginate').hide();
        }
    });

    // ── Custom search (debounced 400ms) ──
    let searchTimer;
    $('#dtSearch').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => { table.search(this.value).draw(); }, 400);
    });

    // ── Per-page selector ──
    $('#perPageSelect').on('change', function () {
        table.page.len(+this.value).draw();
    });

    // ── Filter pills ──
    $(document).on('click', '.filter-pill[data-prov]', function () {
        $('.filter-pill').removeClass('active');
        $(this).addClass('active');
        provFilter = $(this).data('prov');
        table.ajax.reload();
    });

    // ── Dropdown provisioning status ──
    function bindDropdowns() {
        // Toggle open/close
        $(document).off('click.provBtn').on('click.provBtn', function (e) {
            const btn = $(e.target).closest('.prov-status-btn');
            $('.prov-dropdown').hide();
            if (btn.length) {
                const uid  = btn.data('user-id');
                $(`.prov-dropdown[data-user-id="${uid}"]`).toggle();
                e.stopPropagation();
            }
        });

        // Change status via AJAX
        $(document).off('click.provOpt').on('click.provOpt', '.prov-status-option', function () {
            const status = $(this).data('status');
            const url    = $(this).data('url');

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    $('.prov-dropdown').hide();
                    Swal.fire({
                        toast: true, position: 'top-end',
                        showConfirmButton: false, timer: 3000,
                        icon: 'success', title: 'Status diperbarui',
                        html: res.message,
                    });
                    table.ajax.reload(null, false); // reload tanpa reset halaman
                }
            })
            .catch(() => Swal.fire('Error', 'Gagal mengubah status.', 'error'));
        });
    }
});
</script>

<style>
@keyframes spin-dt { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
</style>
@endpush