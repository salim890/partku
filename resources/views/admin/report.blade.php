@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    .table th { background: #f8f9fa; font-size: 0.85rem; color: #555; }
    .table td { font-size: 0.9rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    {{-- Header & Tombol Kembali --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ $title }}</h3>
            <p class="text-muted mb-0">Total Data: {{ $data->total() }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary fw-bold">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Form Search & Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.report', $type) }}" method="GET" class="row g-3 align-items-end">
                
                {{-- Search Bar --}}
                <div class="col-md-5">
                    <label class="form-label fw-bold small">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama / nomor order / email..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Filter Tanggal (Hanya muncul untuk tipe order) --}}
                @if($type == 'revenue' || $type == 'orders' || $type == 'pending')
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                @endif

                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100 fw-bold">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Dinamis --}}
    <div class="card border-0 shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover border align-middle">
                
                {{-- Header Tabel Berdasarkan Tipe --}}
                <thead>
                    <tr>
                        @if($type == 'revenue' || $type == 'orders' || $type == 'pending')
                            <th>No. Order</th>
                            <th>Pembeli</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Invoice</th>
                        @elseif($type == 'customers')
                            <th>Nama Pelanggan</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                        @elseif($type == 'products')
                            <th>Produk</th>
                            <th>No. Order</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        @endif
                    </tr>
                </thead>

                {{-- Isi Tabel Berdasarkan Tipe --}}
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            @if($type == 'revenue' || $type == 'orders' || $type == 'pending')
                                <td class="fw-bold">{{ $item->order_number }}</td>
                                <td>{{ $item->customer_name }}<br><small class="text-muted">{{ $item->customer_phone }}</small></td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="text-danger fw-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->status == 'pending') <span class="badge bg-warning text-dark">PENDING</span>
                                    @elseif($item->status == 'dikirim') <span class="badge bg-info text-dark">DIKIRIM</span>
                                    @else <span class="badge bg-success">SELESAI</span> @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.order.invoice', $item->id) }}" class="btn btn-sm btn-outline-dark" target="_blank"><i class="bi bi-printer"></i></a>
                                </td>
                            
                            @elseif($type == 'customers')
                                <td class="fw-bold">{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                            
                            @elseif($type == 'products')
                                <td class="fw-bold">{{ $item->product->name }}</td>
                                <td>{{ $item->order->order_number }}</td>
                                <td>{{ $item->qty }} pcs</td>
                                <td class="fw-bold">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection