@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Setup Wizard Onboarding</h1>
            <p class="text-gray-500 text-sm">Registrasi User, Server, dan Database dalam satu langkah.</p>
        </div>
    </div>

    <!-- Stepper Indicator -->
    <div class="mb-8">
        <ul class="flex justify-between items-center w-full max-w-3xl mx-auto relative">
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 z-0"></div>
            <div id="progress-bar" class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-primary z-0 transition-all duration-300" style="width: 0%;"></div>
            
            <li class="relative z-10 flex flex-col items-center">
                <div id="step1-icon" class="w-10 h-10 rounded-full flex items-center justify-center bg-primary text-white font-bold border-4 border-white shadow">1</div>
                <span class="mt-2 text-sm font-semibold text-primary">Data User</span>
            </li>
            <li class="relative z-10 flex flex-col items-center">
                <div id="step2-icon" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-500 font-bold border-4 border-white shadow transition-colors">2</div>
                <span class="mt-2 text-sm font-semibold text-gray-500" id="step2-text">Koneksi Server</span>
            </li>
            <li class="relative z-10 flex flex-col items-center">
                <div id="step3-icon" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-500 font-bold border-4 border-white shadow transition-colors">3</div>
                <span class="mt-2 text-sm font-semibold text-gray-500" id="step3-text">Pilih Database</span>
            </li>
        </ul>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="box custom-box shadow-lg">
            <div class="box-body p-6">
                <!-- Data Penampung Tersembunyi -->
                <input type="hidden" id="created_user_id" value="">
                <input type="hidden" id="created_server_id" value="">

                <!-- STEP 1: Form User -->
                <div id="step-1-form">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2"><i class="ri-user-add-line mr-2"></i>Langkah 1: Informasi User Klien</h3>
                    <form id="form-user">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="form-label font-semibold">Nama Klien / Perusahaan</label>
                                <input type="text" class="form-control" name="name" required placeholder="Contoh: PT. Maju Bersama">
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Username Login</label>
                                <input type="text" class="form-control" name="username" required placeholder="Contoh: majubersama">
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Email Klien</label>
                                <input type="email" class="form-control" name="email" required placeholder="klien@example.com">
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Password</label>
                                <input type="password" class="form-control" name="password" required placeholder="Minimal 6 karakter">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="ti-btn ti-btn-primary-full" id="btn-next-1">
                                Lanjut ke Koneksi Server <i class="ri-arrow-right-line ml-1"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 2: Form Server -->
                <div id="step-2-form" class="hidden">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2"><i class="ri-server-line mr-2"></i>Langkah 2: Setup Koneksi Tailscale (Server)</h3>
                    <form id="form-server">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4 md:col-span-2">
                                <label class="form-label font-semibold">Nama Server Klien</label>
                                <input type="text" class="form-control" name="server_name" required placeholder="Contoh: Server Kasir Induk Klien A">
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">IP Tailscale Klien</label>
                                <input type="text" class="form-control" name="ip_address" required placeholder="100.x.x.x">
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Port MySQL</label>
                                <input type="number" class="form-control" name="port" value="3306" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Username MySQL Klien</label>
                                <input type="text" class="form-control" name="username" required value="dashmo">
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Password MySQL Klien</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-xs text-gray-500 italic">*Pastikan database klien sudah di-GRANT untuk user ini</span>
                            <button type="submit" class="ti-btn ti-btn-success-full" id="btn-next-2">
                                <i class="ri-radar-line mr-1"></i> Test & Scan Database
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 3: Form Database -->
                <div id="step-3-form" class="hidden">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2"><i class="ri-database-2-line mr-2"></i>Langkah 3: Pilih Database Aktif</h3>
                    <div class="alert alert-success bg-success/10 text-success border-success/20 mb-4" role="alert">
                        <i class="ri-checkbox-circle-line mr-2"></i>Koneksi Server Berhasil! Silakan pilih database yang akan dikelola.
                    </div>
                    <form id="form-database">
                        <div class="mb-4">
                            <label class="form-label font-semibold">Pilih Database dari Server</label>
                            <select class="form-control" name="db_name" id="db_select" required>
                                <!-- Akan diisi via AJAX -->
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="form-label font-semibold">Jenis Paket / Langganan</label>
                                <select class="form-control" name="package_type" required>
                                    <option value="" disabled selected>-- Pilih Paket --</option>
                                    @foreach($pricingPlans as $plan)
                                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Masa Aktif (Expired At)</label>
                                <input type="date" class="form-control" name="expired_at" value="{{ date('Y-m-d', strtotime('+1 month')) }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Deskripsi (Opsional)</label>
                                <input type="text" class="form-control" name="description" placeholder="Catatan...">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="ti-btn ti-btn-primary-full text-lg px-8 py-2" id="btn-finish">
                                <i class="ri-check-double-line mr-1"></i> Selesaikan Setup!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Setup AJAX CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle Form User (Step 1)
    $('#form-user').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#btn-next-1');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line animate-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ route('setup.wizard.user') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    // Simpan ID user untuk step terakhir
                    $('#created_user_id').val(res.user_id);
                    
                    // Ganti UI ke step 2
                    $('#step-1-form').slideUp();
                    $('#step-2-form').slideDown();
                    
                    // Update Stepper
                    $('#progress-bar').css('width', '50%');
                    $('#step1-icon').removeClass('bg-primary border-white').addClass('bg-success border-success text-white').html('<i class="ri-check-line"></i>');
                    $('#step2-icon').removeClass('bg-gray-200 text-gray-500').addClass('bg-primary text-white');
                    $('#step2-text').removeClass('text-gray-500').addClass('text-primary');

                    Swal.fire({
                        toast: true, position: 'top-end', showConfirmButton: false,
                        timer: 3000, icon: 'success', title: res.message
                    });
                }
            },
            error: function(err) {
                btn.prop('disabled', false).html('Lanjut ke Koneksi Server <i class="ri-arrow-right-line ml-1"></i>');
                let msg = err.responseJSON?.message || 'Terjadi kesalahan validasi.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // Handle Form Server & Scan (Step 2)
    $('#form-server').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#btn-next-2');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line animate-spin mr-1"></i> Scanning IP...');

        $.ajax({
            url: "{{ route('setup.wizard.server') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    // Simpan ID server
                    $('#created_server_id').val(res.server_id);
                    
                    // Isi dropdown database
                    let dbSelect = $('#db_select');
                    dbSelect.empty();
                    dbSelect.append('<option value="" disabled selected>-- Pilih Database --</option>');
                    $.each(res.databases, function(index, value) {
                        dbSelect.append('<option value="' + value + '">' + value + '</option>');
                    });

                    // Ganti UI ke step 3
                    $('#step-2-form').slideUp();
                    $('#step-3-form').slideDown();

                    // Update Stepper
                    $('#progress-bar').css('width', '100%');
                    $('#step2-icon').removeClass('bg-primary border-white').addClass('bg-success border-success text-white').html('<i class="ri-check-line"></i>');
                    $('#step3-icon').removeClass('bg-gray-200 text-gray-500').addClass('bg-primary text-white');
                    $('#step3-text').removeClass('text-gray-500').addClass('text-primary');
                }
            },
            error: function(err) {
                btn.prop('disabled', false).html('<i class="ri-radar-line mr-1"></i> Test & Scan Database');
                let msg = err.responseJSON?.message || 'Gagal koneksi ke server. Periksa kembali IP dan Kredensial.';
                Swal.fire('Koneksi Gagal', msg, 'error');
            }
        });
    });

    // Handle Form Database (Step 3 - Final)
    $('#form-database').on('submit', function(e) {
        e.preventDefault();
        let btn = $('#btn-finish');
        btn.prop('disabled', true).html('<i class="ri-loader-4-line animate-spin mr-1"></i> Menyelesaikan...');

        let formData = $(this).serializeArray();
        formData.push({name: "user_id", value: $('#created_user_id').val()});
        formData.push({name: "server_id", value: $('#created_server_id').val()});

        $.ajax({
            url: "{{ route('setup.wizard.database') }}",
            type: "POST",
            data: $.param(formData),
            success: function(res) {
                if(res.status === 'success') {
                    $('#step3-icon').removeClass('bg-primary border-white').addClass('bg-success border-success text-white').html('<i class="ri-check-line"></i>');
                    
                    Swal.fire({
                        title: 'Setup Selesai!',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: 'Kembali ke Dashboard',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('available_database.index') }}";
                        }
                    });
                }
            },
            error: function(err) {
                btn.prop('disabled', false).html('<i class="ri-check-double-line mr-1"></i> Selesaikan Setup!');
                let msg = err.responseJSON?.message || 'Gagal mengaitkan database.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });
});
</script>
@endpush
