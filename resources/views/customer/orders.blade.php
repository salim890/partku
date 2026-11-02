@extends('layouts.app')
@section('title', 'Pesanan Saya')

@push('styles')
<style> 
    body { background-color: #f4f4f4; } 
    .modal-backdrop { z-index: 1040; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4">Riwayat Pesanan Saya</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @forelse($orders as $order)
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <strong class="text-dark">{{ $order->order_number }}</strong><br>
                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                </div>
                                <div>
                    @if($order->status == 'pending')
                        <span class="badge bg-warning text-dark">MENUNGGU PEMBAYARAN</span>
                    @elseif($order->status == 'paid')
                        <span class="badge bg-success">LUNAS (DIBAYAR)</span>
                    @elseif($order->status == 'packed')
                        <span class="badge bg-primary">SEDANG DIKEMAS</span>
                    @elseif($order->status == 'shipped')
                        <span class="badge bg-info text-dark">SEDANG DIKIRIM</span>
                    @elseif($order->status == 'completed')
                        <span class="badge bg-success">SELESAI</span>
                    @else
                        <span class="badge bg-danger">DIBATALKAN</span>
                    @endif
                </div>
            </div>
            
            <div class="card-body">
                {{-- Tampilkan Resi Jika Ada --}}
                @if($order->tracking_number)
                    <div class="alert alert-info py-2 mb-3 d-flex align-items-center">
                        <i class="bi bi-truck me-2 fs-5"></i> 
                        <span>No. Resi Pengiriman: <strong>{{ $order->tracking_number }}</strong> ({{ $order->shipping_method }})</span>
                    </div>
                @endif
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                        <img src="{{ $item->product->image_url }}" style="width: 60px; height: 60px; object-fit: contain;" class="me-3 border rounded">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $item->product->name }}</div>
                            <small class="text-muted">{{ $item->qty }} barang x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                            
                            {{-- TOMBOL ULASAN (Hanya muncul jika status 'selesai') --}}
                            @if($order->status == 'selesai')
                                @php
                                    $sudahReview = \App\Models\Review::where('user_id', auth()->id())->where('product_id', $item->product_id)->where('order_id', $order->id)->exists();
                                @endphp
                                
                                @if(!$sudahReview)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-warning fw-bold" data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $order->id }}-{{ $item->product_id }}">
                                            <i class="bi bi-star-fill"></i> Beri Ulasan
                                        </button>
                                    </div>

                                    <!-- Modal Ulasan -->
                                    <div class="modal fade" id="reviewModal-{{ $order->id }}-{{ $item->product_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('review.store', [$order->id, $item->product_id]) }}" method="POST" class="modal-content">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ulasan untuk {{ $item->product->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Rating Bintang</label>
                                                        <select name="rating" class="form-select" required>
                                                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                                                            <option value="3">⭐⭐⭐ (Cukup)</option>
                                                            <option value="2">⭐⭐ (Kurang)</option>
                                                            <option value="1">⭐ (Sangat Kurang)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Komentar (Opsional)</label>
                                                        <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan pengalamanmu menggunakan produk ini..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-warning fw-bold w-100">Kirim Ulasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-success mt-2">Sudah Diulas</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach

                <hr>
                
                {{-- Total dan Tombol Detail --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="text-muted">Total Belanja:</span>
                    <div class="d-flex align-items-center gap-3">
                        <strong class="text-danger fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-outline-danger btn-sm fw-bold">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 bg-white rounded shadow-sm">
            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
            <h5 class="mt-3">Belum ada pesanan</h5>
            <a href="{{ route('katalog') }}" class="btn btn-danger mt-3">Mulai Belanja</a>
        </div>
    @endforelse
</div>
@endsection