@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    
    /* Perbaikan Kotak Statistik */
    .stat-card { 
        border-radius: 12px; padding: 20px; color: white; 
        display: block; transition: 0.3s; position: relative; overflow: hidden;
        cursor: pointer;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); color: white; }
    .stat-card i { position: absolute; right: -10px; bottom: -20px; font-size: 6rem; opacity: 0.15; }
    .stat-title { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; opacity: 0.9; }
    .stat-value { font-size: 1.8rem; font-weight: 800; margin-bottom: 0; }
    
    .bg-blue { background: linear-gradient(135deg, #0d6efd, #0043a8); }
    .bg-green { background: linear-gradient(135deg, #198754, #0f5132); }
    .bg-orange { background: linear-gradient(135deg, #fd7e14, #d63384); }
    .bg-purple { background: linear-gradient(135deg, #6f42c1, #4b2b8a); }
    .bg-teal { background: linear-gradient(135deg, #20c997, #138f6a); }
    .bg-dark { background: linear-gradient(135deg, #343a40, #1a1d20); }
    
    .table th { background: #f8f9fa; font-size: 0.85rem; color: #555; }
    .table td { font-size: 0.9rem; vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Dashboard Admin</h3>
            <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }} 👋</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger fw-bold">Pesanan</a>
            <a href="{{ route('admin.logs') }}" class="btn btn-outline-danger fw-bold">Log Aktivitas</a>
            {{-- TOMBOL KELOLA PRODUK (Cuma 1 Sekarang) --}}
            <a href="{{ route('admin.products.index') }}" class="btn btn-dark fw-bold">Kelola Produk</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger fw-bold">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
    @endif

    {{-- 6 KARTU STATISTIK (SUDAH RAPI 3 PER BARIS) --}}
    <div class="row g-3 mb-5">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.report', 'revenue') }}" class="stat-card bg-blue">
                <i class="bi bi-wallet2"></i>
                <div class="stat-title">Total Pendapatan</div>
                <p class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.report', 'orders') }}" class="stat-card bg-green">
                <i class="bi bi-cart-check"></i>
                <div class="stat-title">Pesanan Sukses</div>
                <p class="stat-value">{{ $totalOrders }} Transaksi</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.report', 'revenue') }}" class="stat-card bg-orange">
                <i class="bi bi-receipt"></i>
                <div class="stat-title">AOV (Rata-rata Belanja)</div>
                <p class="stat-value">Rp {{ number_format($aov, 0, ',', '.') }}</p>
            </a>
        </div>
        
         <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.report', 'pending') }}" class="stat-card bg-purple">
                <i class="bi bi-hourglass-split"></i>
                <div class="stat-title">Pesanan Pending</div>
                <p class="stat-value">{{ $pendingOrders }} Pesanan</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.report', 'customers') }}" class="stat-card bg-teal">
                <i class="bi bi-people"></i>
                <div class="stat-title">Total Pelanggan</div>
                <p class="stat-value">{{ $totalCustomers }} Orang</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.report', 'products') }}" class="stat-card bg-dark">
                <i class="bi bi-box-seam"></i>
                <div class="stat-title">Produk Terjual</div>
                <p class="stat-value">{{ $totalItemsSold }} pcs</p>
            </a>
        </div>
    </div>

    {{-- TABEL PESANAN --}}
    <div class="card border-0 shadow-sm p-4" id="tabel-pesanan">
        <h5 class="fw-bold mb-3">Daftar Pesanan Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover border align-middle">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="150">Aksi (Ubah Status)</th>
                        <th width="80" class="text-center">Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold">{{ $order->order_number }}</td>
                        <td>
                            {{ $order->customer_name }}<br>
                            <small class="text-muted">{{ $order->customer_phone }}</small>
                        </td>
                        <td class="text-danger fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark">PENDING</span>
                            @elseif($order->status == 'dikirim')
                                <span class="badge bg-info text-dark">DIKIRIM</span>
                            @else
                                <span class="badge bg-success">SELESAI</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.order.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="d-flex flex-column gap-1">
                                                                        <select name="status" class="form-select form-select-sm">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                                        <option value="packed" {{ $order->status == 'packed' ? 'selected' : '' }}>Packed (Dikemas)</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (Dikirim)</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                                    </select>
                                    <input type="text" name="tracking_number" class="form-control form-control-sm" placeholder="No. Resi" value="{{ $order->tracking_number }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Update</button>
                                </div>
                            </form>
                        </td>
                        <td class="text-center">
                            {{-- TOMBOL LIHAT DETAIL INVOICE (SUDAH DIPERBAIKI) --}}
                            <a href="{{ route('admin.order.invoice', $order->id) }}" class="btn btn-sm btn-outline-dark" target="_blank" title="Lihat Invoice">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        {{-- JURNAL AKUNTANSI (SIA) - BAB 6 --}}
    <div class="card border-0 shadow-sm p-4 mt-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-journal-text text-primary"></i> Jurnal Umum (SIA)</h5>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No. Order</th>
                        <th>Akun</th>
                        <th class="text-end">Debit (Rp)</th>
                        <th class="text-end">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\DB::table('journal_entries')->join('orders', 'orders.id', '=', 'journal_entries.order_id')->orderBy('journal_entries.id', 'desc')->take(5)->get() as $j)
                    <tr>
                        <td>{{ $j->order_number }}</td>
                        <td>{{ $j->account_name }}</td>
                        <td class="text-end">{{ $j->debit > 0 ? number_format($j->debit, 0, ',', '.') : '-' }}</td>
                        <td class="text-end">{{ $j->credit > 0 ? number_format($j->credit, 0, ',', '.') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')

@endpush
@endsection