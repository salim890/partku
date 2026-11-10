@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    .admin-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 20px; }
    .table th { background: #f8f9fa; font-size: 0.85rem; color: #555; }
    .table td { font-size: 0.8rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Log Aktivitas Web</h3>
            <p class="text-muted mb-0">Mencatat semua pergerakan di website (Access, Security, & Action Log)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger fw-bold">Kembali ke Pesanan</a>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                        <th>Browser (User Agent)</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="white-space: nowrap;">{{ $log->created_at->format('d/m/y H:i:s') }}</td>
                        <td>
                            @if($log->user)
                                <span class="badge bg-primary">{{ $log->user->name }}</span>
                            @else
                                <span class="badge bg-secondary">Guest</span>
                            @endif
                        </td>
                        <td>
                            @if($log->action == 'LOGIN_SUCCESS')
                                <span class="badge bg-success">LOGIN</span>
                            @elseif($log->action == 'LOGIN_FAILED')
                                <span class="badge bg-danger">GAGAL LOGIN</span>
                            @elseif($log->action == 'CHECKOUT')
                                <span class="badge bg-warning text-dark">CHECKOUT</span>
                            @else
                                <span class="badge bg-light text-dark border">{{ $log->action }}</span>
                            @endif
                        </td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">
                            {{ $log->user_agent ?? '-' }}
                        </td>
                        <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->url }}">
                            {{ $log->url ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada log aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection