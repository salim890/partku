@extends('layouts.app')

@section('title', 'Pembayaran Gateway')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                
                {{-- Header Portal --}}
                <div class="bg-dark text-white p-4 text-center">
                    <h4 class="fw-bold mb-1"><i class="bi bi-shield-lock-fill"></i> Secure Payment Gateway</h4>
                    <p class="mb-0 text-muted small">Simulasi Integrasi Midtrans / Xendit</p>
                </div>

                {{-- Body Portal --}}
                <div class="card-body p-5 text-center">
                    <p class="text-muted mb-1">Total Tagihan</p>
                    <h1 class="fw-bold text-danger mb-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h1>
                    
                    <div class="alert alert-light border mb-4 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">No. Order</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Metode</span>
                            <strong>{{ $order->payment_method }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-warning text-dark">PENDING</span>
                        </div>
                    </div>

                    <form action="{{ route('payment.gateway.pay', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3" onclick="this.innerHTML='Memproses...'; this.disabled=true; this.form.submit();">
                            <i class="bi bi-check-circle-fill"></i> Konfirmasi Pembayaran Lunas
                        </button>
                    </form>
                    
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-lock-fill"></i> Transaksi ini diamankan dengan enkripsi 256-bit (Simulasi).
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection