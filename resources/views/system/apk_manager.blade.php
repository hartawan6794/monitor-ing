@extends('layouts.app')

@push('styles')
<style>
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        padding: 3rem 2rem;
        text-align: center;
        background-color: #f8fafc;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }
    .upload-area:hover, .upload-area.dragover {
        border-color: #6366f1;
        background-color: #eef2ff;
    }
    .upload-area input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    .file-icon {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 1rem;
        transition: color 0.3s;
    }
    .upload-area:hover .file-icon {
        color: #6366f1;
    }
    .glass-stat {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-2 p-md-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="font-weight-bold mb-1">Manajemen Aplikasi Android (APK)</h4>
            <p class="text-muted">Kelola versi terbaru aplikasi DashMo untuk didownload klien melalui halaman utama.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="ri-checkbox-circle-line me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="ri-error-warning-line me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Status APK Saat Ini -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="bg-primary text-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 font-weight-bold">Status APK Live</h5>
                        <i class="ri-android-line display-6 opacity-50"></i>
                    </div>
                    @if($apkInfo['exists'])
                        <span class="badge bg-success bg-opacity-25 text-white border border-success px-3 py-2 rounded-pill">
                            <span class="spinner-grow spinner-grow-sm me-1 text-success" role="status" aria-hidden="true" style="width: 0.5rem; height: 0.5rem;"></span>
                            Tersedia (Live)
                        </span>
                    @else
                        <span class="badge bg-danger bg-opacity-25 text-white border border-danger px-3 py-2 rounded-pill">
                            Belum Ada APK
                        </span>
                    @endif
                </div>
                
                <div class="card-body p-4 bg-light">
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="glass-stat p-3 h-100 text-center shadow-sm">
                                <p class="text-muted small text-uppercase mb-1">Ukuran File</p>
                                <h4 class="mb-0 font-weight-bold text-dark">{{ $apkInfo['size'] }} <small class="text-muted" style="font-size: 0.5em">MB</small></h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="glass-stat p-3 h-100 text-center shadow-sm">
                                <p class="text-muted small text-uppercase mb-1">Update Terakhir</p>
                                <h6 class="mb-0 font-weight-bold text-dark lh-sm" style="font-size: 0.9rem;">
                                    {!! str_replace(', ', '<br>', $apkInfo['last_modified']) !!}
                                </h6>
                            </div>
                        </div>
                    </div>

                    @if($apkInfo['exists'])
                    <div class="d-grid gap-2">
                        <a href="{{ $apkInfo['download_url'] }}" class="btn btn-outline-primary rounded-pill fw-medium" target="_blank">
                            <i class="ri-download-cloud-2-line me-1"></i> Coba Download APK
                        </a>
                    </div>
                    <p class="text-center text-muted small mt-3 mb-0">
                        File tersimpan sebagai: <code>dashmo-latest.apk</code>
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Upload -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 font-weight-bold">Update Versi APK</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('system.apk_manager.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="upload-area mb-4" id="dropZone">
                            <input type="file" name="apk_file" id="apkInput" accept=".apk">
                            <i class="ri-file-upload-line file-icon"></i>
                            <h5 class="mb-2 text-dark font-weight-bold">Pilih file atau Tarik & Lepas (Drag & Drop)</h5>
                            <p class="text-muted small mb-0">Hanya format .apk (Maks. 100MB)</p>
                            
                            <!-- File preview state -->
                            <div id="filePreview" class="mt-3 d-none">
                                <div class="d-inline-flex align-items-center gap-2 bg-white px-4 py-2 rounded-pill shadow-sm border">
                                    <i class="ri-android-fill text-success fs-4"></i>
                                    <span id="fileName" class="font-weight-medium text-dark"></span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 shadow-sm bg-warning bg-opacity-10 mb-4 text-dark">
                            <i class="ri-error-warning-fill text-warning me-2 fs-5 align-middle"></i>
                            <strong>Peringatan:</strong> Mengunggah APK baru akan <b>menimpa</b> file APK versi lama yang sudah ada. Link download di halaman utama akan langsung mengarah ke versi baru ini.
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm" id="btnUpload" disabled>
                                <i class="ri-upload-cloud-2-line me-1"></i> Unggah & Terapkan Update
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
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('apkInput');
        const dropZone = document.getElementById('dropZone');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const btnUpload = document.getElementById('btnUpload');

        // Handle file selection
        input.addEventListener('change', function(e) {
            handleFiles(this.files);
        });

        // Drag and drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('dragover');
        }

        function unhighlight(e) {
            dropZone.classList.remove('dragover');
        }

        dropZone.addEventListener('drop', function(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            input.files = files; // Assign to input
            handleFiles(files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                
                // Validate extension
                if(!file.name.toLowerCase().endsWith('.apk')) {
                    alert('Harap masukkan file dengan format .apk');
                    input.value = '';
                    filePreview.classList.add('d-none');
                    btnUpload.disabled = true;
                    return;
                }

                fileName.textContent = file.name + ' (' + (file.size / 1048576).toFixed(2) + ' MB)';
                filePreview.classList.remove('d-none');
                btnUpload.disabled = false;
            } else {
                filePreview.classList.add('d-none');
                btnUpload.disabled = true;
            }
        }
        
        // Show loading state on form submit
        document.getElementById('uploadForm').addEventListener('submit', function() {
            btnUpload.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengunggah...';
            btnUpload.disabled = true;
        });
    });
</script>
@endpush
