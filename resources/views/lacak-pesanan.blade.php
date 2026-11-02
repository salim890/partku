@extends('layouts.app')

@section('title', 'Lacak Pesanan')

@section('content')
<div class="container py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 8px;">
                <h4 class="fw-bold text-center mb-4">Lacak Pesanan</h4>
                
                {{-- Form Pencarian --}}
                <form action="{{ route('order.track.result') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Order</label>
                        <input type="text" name="order_number" class="form-control" placeholder="Contoh: PK-20261021-1234" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email (Saat Checkout)</label>
                        <input type="email" name="email" class="form-control" placeholder="Contoh: budi@gmail.com" required>
                    </div>
                    
                    {{-- Tampilkan Error Jika Ada --}}
                    @if(session('error'))
                        <div class="alert alert-danger py-2 mb-3 small">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                        <i class="bi bi-search"></i> Lacak Pesanan
                    </button>
                </form>

                {{-- Hasil Pencarian (Jika Pesanan Ditemukan) --}}
                @if(isset($order))
                    <hr>
                    <div class="alert alert-success border-success shadow-sm">
                        <h6 class="fw-bold mb-1"><i class="bi bi-check-circle-fill"></i> Pesanan Ditemukan!</h6>
                        <p class="mb-0 small">Status: <strong>{{ strtoupper($order->status) }}</strong></p>
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">No. Order</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tanggal</span>
                            <strong>{{ $order->created_at->format('d M Y H:i') }} WIB</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total</span>
                            <strong class="text-danger">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </div>
                        
                        @if($order->tracking_number)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">No. Resi</span>
                                <strong>{{ $order->tracking_number }}</strong>
                            </div>
                        @endif
                        
                        <hr>
                        <h6 class="fw-bold small mb-2">Item Pesanan:</h6>
                        <ul class="small text-muted mb-0 ps-3">
                            @foreach($order->items as $item)
                                <li>{{ $item->product_name_snapshot }} ({{ $item->qty }}x)</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection