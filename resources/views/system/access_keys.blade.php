@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Database Access Keys (Sesi API)</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Server</th>
                                    <th>Database</th>
                                    <th>Access Key</th>
                                    <th>Device ID</th>
                                    <th>Dibuat</th>
                                    <th>Expired</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($keys as $key)
                                    <tr>
                                        <td>{{ $loop->iteration + $keys->firstItem() - 1 }}</td>
                                        <td>{{ $key->user->name ?? 'N/A' }} <br><small class="text-muted">{{ $key->user->email ?? '' }}</small></td>
                                        <td>{{ $key->availableDatabase->server->name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-primary-transparent">{{ $key->availableDatabase->db_name ?? 'N/A' }}</span></td>
                                        <td>
                                            <div class="input-group input-group-sm" style="max-width: 200px;">
                                                <input type="text" class="form-control" value="{{ $key->access_key }}" readonly>
                                            </div>
                                        </td>
                                        <td>{{ $key->device_id ?? '-' }}</td>
                                        <td>{{ $key->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $key->expires_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($key->expires_at->isFuture())
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Expired</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">Belum ada access key yang dibuat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $keys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
