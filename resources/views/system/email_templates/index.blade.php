@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-20 mb-0">Template Email</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Sistem</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Template Email</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card custom-card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background: rgba(99, 102, 241, 0.05); border-bottom: 1px solid rgba(99, 102, 241, 0.1); padding: 1.25rem 1.5rem;">
                        <div class="card-title fw-bold text-primary d-flex align-items-center gap-2 mb-0"
                            style="font-size: 1.1rem;">
                            <span
                                style="width: 32px; height: 32px; background: linear-gradient(135deg, #6366f1, #22d3ee); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: white;">
                                <i class="bx bx-envelope fs-18"></i>
                            </span>
                            Pengaturan Template Pengingat
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert"
                                style="border-radius: 12px; background: rgba(52, 211, 153, 0.1); border-color: rgba(52, 211, 153, 0.2); color: #059669;">
                                <i class="bx bx-check-circle fs-20 me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('system.email_template.update') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted"
                                    style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Subjek
                                    Email</label>
                                <input type="text" name="subject"
                                    class="form-control form-control-lg bg-light border-0 shadow-none"
                                    value="{{ old('subject', $template->subject) }}" required
                                    style="border-radius: 10px; font-size: 1rem; padding: 0.75rem 1rem;">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted"
                                    style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Isi Pesan
                                    Utama</label>
                                <textarea name="body" class="form-control bg-light border-0 shadow-none" rows="8" required
                                    style="border-radius: 10px; font-size: 0.95rem; padding: 1rem; line-height: 1.6;">{{ old('body', $template->body) }}</textarea>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-5">
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2"
                                    style="padding: 0.6rem 1.5rem; border-radius: 10px; font-weight: 600;">
                                    <i class="bx bx-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ url('/preview-email-reminder') }}" target="_blank"
                                    class="btn btn-outline-secondary d-inline-flex align-items-center gap-2"
                                    style="padding: 0.6rem 1.5rem; border-radius: 10px; font-weight: 600;">
                                    <i class="bx bx-show"></i> Lihat Preview
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card custom-card border-0 shadow-sm"
                    style="border-radius: 16px; background: linear-gradient(145deg, #f8fafc, #f1f5f9);">
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold text-dark d-flex align-items-center gap-2 mb-4">
                            <i class="bx bx-code-alt text-primary fs-20"></i> Variabel Dinamis
                        </h6>
                        <p class="text-muted mb-4" style="font-size: 0.9rem;">
                            Gunakan kode khusus ini di dalam subjek atau isi pesan. Kode akan otomatis diganti dengan data
                            pelanggan saat email dikirim.
                        </p>

                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 shadow-sm border border-light">
                                <span class="badge bg-primary-transparent text-primary px-2 py-1 fs-12 fw-semibold"
                                    style="border-radius: 6px;">[USER_NAME]</span>
                                <span class="text-muted fs-13 mt-1">Menampilkan nama pelanggan.</span>
                            </li>
                            <li class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 shadow-sm border border-light">
                                <span class="badge bg-warning-transparent text-warning px-2 py-1 fs-12 fw-semibold"
                                    style="border-radius: 6px;">[DAYS_LEFT]</span>
                                <span class="text-muted fs-13 mt-1">Sisa hari sebelum kedaluwarsa.</span>
                            </li>
                            <li class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 shadow-sm border border-light">
                                <span class="badge bg-success-transparent text-success px-2 py-1 fs-12 fw-semibold"
                                    style="border-radius: 6px;">[PLAN_NAME]</span>
                                <span class="text-muted fs-13 mt-1">Nama paket yang sedang aktif.</span>
                            </li>
                            <li class="d-flex align-items-start gap-3 p-3 bg-white rounded-3 shadow-sm border border-light">
                                <span class="badge bg-info-transparent text-info px-2 py-1 fs-12 fw-semibold"
                                    style="border-radius: 6px;">[EXPIRED_DATE]</span>
                                <span class="text-muted fs-13 mt-1">Tanggal kedaluwarsa lengkap.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card custom-card border-0 bg-primary mt-4" style="border-radius: 16px;visibility: hidden;">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <!-- Background accent -->
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="bx bxs-check-shield" style="font-size: 8rem;"></i>
                        </div>

                        <div class="position-relative z-1">
                            <h6 class="fw-bold text-white d-flex align-items-center gap-2 mb-3">
                                <i class="bx bx-shield-quarter fs-20"></i> Layout Aman
                            </h6>
                            <p class="text-white-50 mb-0" style="font-size: 0.9rem; line-height: 1.6;">
                                Anda tidak perlu khawatir desain email rusak. Logo DashMo, background, label status, dan
                                footer dikunci secara otomatis oleh sistem. Anda hanya mengubah bagian pesan utamanya saja.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection