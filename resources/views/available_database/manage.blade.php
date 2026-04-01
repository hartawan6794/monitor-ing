@extends('layouts.app')

@section('title', 'Manage Databases - ' . $server->server_name)

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
            <li class="text-[0.813rem] font-semibold text-defaulttextcolor dark:text-[#8c9097]" aria-current="page">Manage
            </li>
        </ol>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-7">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="box-title">Database Terdaftar di Sistem</div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered w-full">
                            <thead>
                                <tr>
                                    <th>Nama Database</th>
                                    <th>Expired At</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($server->availableDatabases as $db)
                                    <tr>
                                        <td class="font-semibold text-primary">{{ $db->db_name }}</td>
                                        <td>
                                            <span class="text-xs">
                                                {{ $db->expired_at ? \Carbon\Carbon::parse($db->expired_at)->format('d M Y') : '∞ Permanent' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!$db->expired_at)
                                                <span class="badge bg-success/10 text-success">Active</span>
                                            @elseif(\Carbon\Carbon::parse($db->expired_at)->isPast())
                                                <span class="badge bg-danger/10 text-danger">Expired</span>
                                            @else
                                                <span class="badge bg-info/10 text-info">Ongoing</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('available_database.destroy', $db) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="ti-btn ti-btn-sm ti-btn-danger-full confirm-delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box custom-box mt-4 border-t-4 border-warning">
                <div class="box-header">
                    <div class="box-title text-warning"><i class="ri-delete-bin-7-line mr-1"></i> Riwayat Terhapus (Trash)
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table w-full table-bordered">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th>Nama Database</th>
                                    <th>Tgl Dihapus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Ambil data yang hanya terhapus untuk server ini
                                    $trashedDbs = \App\Models\AvailableDatabase::onlyTrashed()
                                        ->where('server_id', $server->id)
                                        ->get();
                                @endphp

                                @forelse($trashedDbs as $trashed)
                                    <tr>
                                        <td class="text-muted"><s>{{ $trashed->db_name }}</s></td>
                                        <td class="text-xs">{{ $trashed->deleted_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('available_database.restore', $trashed->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="ti-btn ti-btn-sm ti-btn-warning-full">
                                                    <i class="ri-restart-line"></i> Pulihkan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-xs text-muted">Tidak ada riwayat penghapusan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-5">
            <div class="box custom-box border-t-4 border-primary">
                <div class="box-header">
                    <div class="box-title">Daftarkan Koneksi Baru</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('available_database.store') }}" method="POST" id="form-add-db">
                        @csrf
                        <input type="hidden" name="server_id" value="{{ $server->id }}">

                        <div class="mb-4">
                            <label class="form-label font-semibold text-sm">Pilih Database di Server</label>
                            <div class="input-group">
                                <select class="form-control !rounded-s-md" id="db_name" name="db_name" required disabled>
                                    <option value="" selected>Scan IP {{ $server->ip_address }}...</option>
                                </select>
                                <button type="button" id="btn-scan" class="ti-btn ti-btn-primary-full !rounded-e-md !mb-0">
                                    <i class="ri-refresh-line"></i> Scan
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-semibold text-sm">Masa Aktif (Expired At)</label>
                            <div class="input-group">
                                <div class="input-group-text text-[#8c9097] dark:text-white/50"> <i
                                        class="ri-calendar-line"></i> </div>
                                <input type="text" name="expired_at" id="expired_at" class="form-control flatpickr-input"
                                    placeholder="Pilih Tanggal Kadaluarsa" readonly>
                            </div>
                            <small class="text-muted text-[10px]">*Kosongkan jika akses tidak terbatas (Permanent)</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-semibold text-sm">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2"
                                placeholder="Catatan untuk database ini..."></textarea>
                        </div>

                        <button type="submit" class="ti-btn ti-btn-success-full w-full justify-center" id="btn-submit"
                            disabled>
                            <i class="ri-add-circle-line mr-1"></i> Simpan Koneksi Database
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function () {
            // Inisialisasi Flatpickr
            flatpickr("#expired_at", {
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: "today", // Tidak bisa pilih tanggal lampau
                animate: true
            });

            // Logika AJAX Scan (Tetap seperti sebelumnya)
            $('#btn-scan').on('click', function () {
                let btn = $(this);
                let dbSelect = $('#db_name');
                let serverId = "{{ $server->id }}";

                btn.addClass('disabled').html('<i class="ri-loader-4-line animate-spin"></i> Scanning...');

                $.ajax({
                    url: '/get-databases-by-server/' + serverId,
                    type: 'GET',
                    success: function (res) {
                        btn.removeClass('disabled').html('<i class="ri-refresh-line"></i> Scan');
                        dbSelect.empty().prop('disabled', false);

                        if (res.status === 'success' && res.data.length > 0) {
                            dbSelect.append('<option value="" disabled selected>-- Pilih Database --</option>');
                            $.each(res.data, function (key, name) {
                                dbSelect.append('<option value="' + name + '">' + name + '</option>');
                            });
                            $('#btn-submit').prop('disabled', false);
                        }
                    },
                    error: function () {
                        btn.removeClass('disabled').text('Scan Gagal');
                    }
                });
            });
            // SweetAlert untuk hapus
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
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush