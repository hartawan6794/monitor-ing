@extends('layouts.app')

@push('styles')
<style>
    .log-entry {
        border-left: 4px solid #6c757d;
        margin-bottom: 1rem;
        background-color: #fff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.25rem;
    }
    .log-level-ERROR, .log-level-CRITICAL, .log-level-EMERGENCY { border-left-color: #dc3545; }
    .log-level-WARNING { border-left-color: #ffc107; }
    .log-level-INFO, .log-level-DEBUG { border-left-color: #0dcaf0; }
    
    .log-header {
        padding: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .log-header:hover {
        background-color: #f8f9fa;
    }
    .log-time {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
    }
    .log-msg {
        font-family: monospace;
        font-size: 0.9rem;
        word-break: break-word;
        color: #212529;
    }
    .log-stack {
        background-color: #212529;
        color: #f8f9fa;
        padding: 1rem;
        margin: 0;
        font-family: monospace;
        font-size: 0.8rem;
        white-space: pre-wrap;
        word-break: break-all;
        border-top: 1px solid #dee2e6;
        display: none;
    }
    
    /* Mobile optimization */
    @media (max-width: 768px) {
        .log-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .log-msg {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-2 p-md-4">
    <div class="row">
        <div class="col-12">
            <div class="card custom-card border-0 shadow-sm">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 bg-white border-bottom pb-3">
                    <div>
                        <h4 class="card-title mb-1 font-weight-bold">System Error Logs</h4>
                        <p class="text-muted small mb-0">
                            Menampilkan maksimal 2000 baris terakhir. <br class="d-md-none">
                            Ukuran file log saat ini: <strong>{{ $logSize ?? 0 }} MB</strong>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('system.logs.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin mengosongkan seluruh log?');">
                            @csrf
                            <button type="submit" class="btn btn-danger shadow-sm">
                                <i class="ri-delete-bin-line me-1"></i> Bersihkan Log
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body bg-light p-2 p-md-4">
                    @if(empty($parsedLogs))
                        <div class="text-center py-5 bg-white rounded shadow-sm">
                            <div class="mb-3">
                                <i class="ri-shield-check-line text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-dark">Sistem Aman Terkendali</h5>
                            <p class="text-muted">Tidak ada error log yang tercatat saat ini.</p>
                        </div>
                    @else
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <i class="ri-information-line me-2"></i> Klik pada pesan error untuk melihat <strong>Stack Trace</strong> (detail lokasi error).
                        </div>
                        
                        <div class="log-container">
                            @foreach($parsedLogs as $index => $log)
                                <div class="log-entry log-level-{{ $log['level'] }}">
                                    <div class="log-header" onclick="toggleStack('stack-{{ $index }}')">
                                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 min-w-fit">
                                            @php
                                                $badgeClass = 'bg-secondary';
                                                if(in_array($log['level'], ['ERROR', 'CRITICAL', 'EMERGENCY'])) $badgeClass = 'bg-danger';
                                                elseif($log['level'] == 'WARNING') $badgeClass = 'bg-warning text-dark';
                                                elseif(in_array($log['level'], ['INFO', 'DEBUG'])) $badgeClass = 'bg-info';
                                            @endphp
                                            <span class="badge {{ $badgeClass }} rounded-pill px-3">{{ $log['level'] }}</span>
                                            <span class="log-time"><i class="ri-time-line me-1"></i>{{ $log['timestamp'] }}</span>
                                        </div>
                                        <div class="log-msg flex-grow-1">
                                            <strong>{{ $log['env'] }}:</strong> {{ \Illuminate\Support\Str::limit($log['message'], 200) }}
                                        </div>
                                        <div class="ms-auto text-muted d-none d-md-block">
                                            <i class="ri-arrow-down-s-line" id="icon-{{ $index }}"></i>
                                        </div>
                                    </div>
                                    @if(!empty(trim($log['stack'])))
                                        <pre class="log-stack" id="stack-{{ $index }}">{{ $log['message'] }}

{!! $log['stack'] !!}</pre>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleStack(id) {
        const stackElement = document.getElementById(id);
        if (stackElement) {
            const icon = document.getElementById(id.replace('stack', 'icon'));
            if (stackElement.style.display === 'block') {
                stackElement.style.display = 'none';
                if(icon) icon.className = 'ri-arrow-down-s-line';
            } else {
                stackElement.style.display = 'block';
                if(icon) icon.className = 'ri-arrow-up-s-line';
            }
        }
    }
</script>
@endpush
