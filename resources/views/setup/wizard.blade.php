@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Setup Wizard Onboarding</h1>
            <p class="text-gray-500 text-sm">
                @if($prefillUser)
                    Melanjutkan setup untuk <strong>{{ $prefillUser->name }}</strong>.
                @else
                    Registrasi User, Server, dan Database dalam satu langkah.
                @endif
            </p>
        </div>
        <a href="{{ route('registered.users') }}" class="ti-btn ti-btn-light flex items-center gap-1 !text-sm !py-1.5 !px-3 !rounded-xl">
            <i class="ri-arrow-left-line"></i> Kembali
        </a>
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

                    @if($prefillUser)
                    {{-- ── Banner: user sudah terdaftar ── --}}
                    <div class="mb-4 rounded-xl p-4 flex items-start gap-3"
                        style="background:rgba(99,102,241,.08);border:1.5px solid rgba(99,102,241,.2);">
                        <div style="width:42px;height:42px;border-radius:50%;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($prefillUser->name, 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700;color:#0f172a;" class="dark:text-white">{{ $prefillUser->name }}</div>
                            <div style="font-size:.82rem;color:#64748b;">{{ $prefillUser->email }} · @{{ $prefillUser->username }}</div>
                            <div class="mt-1" style="font-size:.78rem;color:#6366f1;font-weight:600;">
                                <i class="ri-information-line"></i>
                                User ini sudah terdaftar. Data diisi otomatis — Step 1 akan dilewati.
                            </div>
                        </div>
                        <div>
                            <span style="background:rgba(16,185,129,.12);color:#059669;padding:3px 12px;border-radius:999px;font-size:.72rem;font-weight:700;">Terdaftar</span>
                        </div>
                    </div>
                    @endif

                    <form id="form-user">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="form-label font-semibold">Nama Klien / Perusahaan</label>
                                <input type="text" class="form-control" name="name" required placeholder="Contoh: PT. Maju Bersama"
                                    value="{{ $prefillUser?->name }}" {{ $prefillUser ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Username Login</label>
                                <input type="text" class="form-control" name="username" required placeholder="Contoh: majubersama"
                                    value="{{ $prefillUser?->username }}" {{ $prefillUser ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">Email Klien</label>
                                <input type="email" class="form-control" name="email" required placeholder="klien@example.com"
                                    value="{{ $prefillUser?->email }}" {{ $prefillUser ? 'readonly' : '' }}>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-semibold">
                                    Password
                                    @if($prefillUser)
                                        <span class="text-xs font-normal text-muted ml-1">(tidak perlu diisi ulang)</span>
                                    @endif
                                </label>
                                <input type="password" class="form-control" name="password"
                                    {{ $prefillUser ? '' : 'required' }}
                                    placeholder="{{ $prefillUser ? 'Biarkan kosong untuk skip Step ini' : 'Minimal 6 karakter' }}">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            @if($prefillUser)
                                <span class="text-xs text-indigo-500"><i class="ri-skip-forward-line"></i> Step ini akan dilewati otomatis dalam 3 detik…</span>
                            @else
                                <span></span>
                            @endif
                            <button type="submit" class="ti-btn ti-btn-primary-full" id="btn-next-1">
                                @if($prefillUser)
                                    <i class="ri-arrow-right-line mr-1"></i> Lanjut ke Koneksi Server
                                @else
                                    Lanjut ke Koneksi Server <i class="ri-arrow-right-line ml-1"></i>
                                @endif
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
// Prefill data dari controller
@php
    $prefillData = null;
    if ($prefillUser) {
        $sub = $prefillUser->subscriptions->first();
        $prefillData = [
            'id'         => $prefillUser->id, 
            'name'       => $prefillUser->name,
            'plan_id'    => $sub?->pricing_plan_id ?? '',
            'expires_at' => $sub?->expires_at ? \Carbon\Carbon::parse($sub->expires_at)->format('Y-m-d') : date('Y-m-d', strtotime('+1 month'))
        ];
    }
@endphp
const PREFILL_USER = @json($prefillData);

$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // ── Jika ada prefill user (user sudah terdaftar), skip Step 1 otomatis ──
    if (PREFILL_USER) {
        $('#created_user_id').val(PREFILL_USER.id);

        // Pre-fill Step 3 subscription data
        if (PREFILL_USER.plan_id) {
            $('select[name="package_type"]').val(PREFILL_USER.plan_id);
        }
        if (PREFILL_USER.expires_at) {
            $('input[name="expired_at"]').val(PREFILL_USER.expires_at);
        }

        // Auto-klik submit setelah 3 detik
        let countdown = 3;
        const timer = setInterval(function () {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timer);
                skipToStep2();
            } else {
                $('#btn-next-1').find('.countdown-num').text(countdown);
            }
        }, 1000);

        // Label countdown di tombol
        $('#btn-next-1').html('<i class="ri-arrow-right-line mr-1"></i> Lanjut (<span class="countdown-num">3</span>s...)');

        // Juga bisa manual klik
        $('#btn-next-1').off('click').on('click', function (e) {
            e.preventDefault();
            clearInterval(timer);
            skipToStep2();
        });
    }

    function skipToStep2() {
        $('#step-1-form').slideUp();
        $('#step-2-form').slideDown();
        $('#progress-bar').css('width', '50%');
        $('#step1-icon').removeClass('bg-primary border-white').addClass('bg-success border-success text-white').html('<i class="ri-check-line"></i>');
        $('#step2-icon').removeClass('bg-gray-200 text-gray-500').addClass('bg-primary text-white');
        $('#step2-text').removeClass('text-gray-500').addClass('text-primary');
    }

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
