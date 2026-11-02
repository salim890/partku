@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@push('styles')
<style>
    /* 1. DESAIN UNTUK TAMPILAN WEB (NORMAL) */
    body { background-color: #f4f4f4; }
    .invoice-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 20px; }
    .invoice-header { background: #cc0000; color: white; padding: 20px; text-align: center; }
    .invoice-body { padding: 25px; }
    .invoice-title { font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
    .invoice-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; }
    
    .total-box { background: #fff5f2; border: 1px dashed #ff3c00; padding: 15px; border-radius: 6px; margin-top: 20px; text-align: center; }
    .total-amount { font-size: 1.8rem; font-weight: 800; color: #cc0000; line-height: 1; margin-top: 5px; }

    /* 2. DESAIN TIMER */
    .timer-box { background: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; border: 2px solid #cc0000; }
    .timer-label { font-size: 0.9rem; font-weight: bold; color: #ffffff; letter-spacing: 1px; margin-bottom: 5px; }
    .timer-text { font-size: 2.2rem; font-weight: 800; font-family: monospace; color: #ffc107; letter-spacing: 2px; }
    
    /* 3. DESAIN QRIS */
    .qris-box { border: 2px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; background: #fff; display: inline-block; }
    .qris-img { width: 200px; height: 200px; object-fit: contain; }

    /* 4. CSS PRINT (Agar Rapih Jadi PDF) */
    @media print {
        @page { size: A4 portrait; margin: 1cm; }
        body { background: #fff !important; font-size: 12px !important; margin: 0; padding: 0; }
        .container, .row, .col-lg-7, main { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; display: block !important; }
        nav, footer, .wa-float, .no-print, .timer-box { display: none !important; }
        .invoice-card { box-shadow: none !important; border: 1px solid #000 !important; border-radius: 0 !important; width: 100% !important; margin: 0 !important; page-break-inside: avoid; }
        .invoice-header { background: #fff !important; color: #000 !important; border-bottom: 2px solid #000 !important; padding: 15px !important; text-align: left !important; }
        .invoice-body { padding: 15px !important; }
        .total-box { background: #fff !important; border: 1px solid #000 !important; color: #000 !important; padding: 10px !important; }
        .total-amount { color: #000 !important; font-size: 20px !important; }
        .text-danger, .text-success { color: #000 !important; }
        hr { border-top: 1px solid #000 !important; }
    }
</style>
@endpush

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Header Sukses (Hilang saat print) --}}
            <div class="text-center mb-4 no-print">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h3 class="fw-bold mt-2">Pesanan Berhasil Dibuat!</h3>
                <p class="text-muted">Terima kasih, <strong>{{ $order->customer_name }}</strong>.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success text-center fw-bold no-print">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif

            {{-- COUNTDOWN TIMER (Hanya muncul jika status PENDING) --}}
            @if($order->status === 'pending')
                <div class="timer-box no-print">
                    <div class="timer-label text-uppercase">Selesaikan Pembayaran Dalam</div>
                    <div id="countdown" class="timer-text">Memuat...</div>
                </div>

                
            @endif

            {{-- Kartu Invoice --}}
            <div class="invoice-card">
                <div class="invoice-header">
                    <h4 class="mb-0 fw-bold text-uppercase">Invoice PartKu</h4>
                    <p class="mb-0">{{ $order->order_number }}</p>
                </div>

                <div class="invoice-body">
                    <div class="invoice-row">
                        <span class="text-muted">Status Pesanan</span>
                        <span class="fw-bold text-uppercase">{{ $order->status }}</span>
                    </div>
                    <div class="invoice-row">
                        <span class="text-muted">Tanggal</span>
                        <strong>{{ $order->created_at->format('d F Y, H:i') }} WIB</strong>
                    </div>
                    <div class="invoice-row">
                        <span class="text-muted">Metode Pembayaran</span>
                        <strong class="text-danger">{{ $order->payment_method }}</strong>
                    </div>

                    <hr>

                    <div class="invoice-title">Rincian Belanja</div>
                    @foreach($order->items as $item)
                        <div class="invoice-row mb-3">
                            <div style="max-width: 70%;">
                                <div class="fw-bold">{{ $item->product_name_snapshot }}</div>
                                <small class="text-muted">{{ $item->qty }} x Rp {{ number_format($item->price_snapshot, 0, ',', '.') }}</small>
                            </div>
                            <div class="fw-bold text-end">
                                Rp {{ number_format($item->price_snapshot * $item->qty, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="invoice-title">Dikirim Ke</div>
                    <div class="mb-2">
                        <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_phone }})<br>
                        <span class="text-muted">{{ $order->customer_address }}</span>
                    </div>

                    @if($order->total_amount < $order->items->sum(function($item) { return $item->price_snapshot * $item->qty; }))
                        <div class="invoice-row mt-4">
                            <span class="text-success fw-bold">Diskon (Kupon PARTKU10)</span>
                            <span class="text-success fw-bold">- Rp {{ number_format($order->items->sum(function($item) { return $item->price_snapshot * $item->qty; }) - $order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                                       {{-- Rincian Subtotal & Ongkir --}}
                    <hr>
                    @php
                        // Hitung subtotal asli dari barang
                        $subtotal = $order->items->sum(function($item) { 
                            return ($item->price_snapshot ?? $item->price) * $item->qty; 
                        });
                        // Cari tau berapa diskon yang didapat (jika ada)
                        $discount = $subtotal + $order->shipping_cost - $order->total_amount;
                    @endphp
                    
                    <div class="invoice-row">
                        <span class="text-muted">Subtotal Produk</span>
                        <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="invoice-row">
                        <span class="text-muted">Ongkos Kirim ({{ $order->shipping_method }})</span>
                        <span class="fw-bold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($discount > 0)
                        <div class="invoice-row text-success">
                            <span class="fw-bold">Diskon (Kupon PARTKU10)</span>
                            <span class="fw-bold">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    {{-- Cuma Ada SATU Kotak Total Tagihan --}}
                    <div class="total-box">
                        <div class="text-muted small">Total Tagihan</div>
                        <div class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    </div>

                    {{-- INSTRUKSI PEMBAYARAN DINAMIS (Hanya untuk PENDING) --}}
                    @if($order->status === 'pending')
                        <div class="mt-4 mb-4 p-3 bg-light border rounded">
                            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary"></i> Instruksi Pembayaran:</h6>
                            
                            @if($order->payment_method == 'QRIS')
                                <div class="text-center">
                                    <p class="small mb-2">Scan QR Code di bawah ini menggunakan aplikasi M-Banking atau E-Wallet.</p>
                                    <div class="qris-box">
                                        <img src="{{ asset('images/qris-toko.png') }}" alt="QRIS PartKu" class="qris-img">
                                    </div>
                                    <p class="small text-muted mt-2">Nominal: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>
                                </div>
                            @elseif($order->payment_method == 'Transfer Bank BCA')
                                <p class="small mb-0">Silakan transfer ke rekening <strong>BCA</strong>:<br>No. Rek: <strong>1343263515</strong><br>Atas Nama: <strong>Salim</strong></p>
                            @elseif($order->payment_method == 'Transfer Bank BSI')
                                <p class="small mb-0">Silakan transfer ke rekening <strong>BSI</strong>:<br>No. Rek: <strong>7186446067</strong><br>Atas Nama: <strong>Salim</strong></p>
                            @elseif($order->payment_method == 'Transfer Bank Seabank')
                                <p class="small mb-0">Silakan transfer ke rekening <strong>Seabank</strong>:<br>No. Rek: <strong>901020196918</strong><br>Atas Nama: <strong>Salim</strong></p>
                            @elseif($order->payment_method == 'E-Wallet')
                                <p class="small mb-0">Silakan kirim pembayaran via DANA / OVO / GoPay ke nomor:<br>No. HP: <strong>082129626596</strong><br>Atas Nama: <strong>Salim</strong></p>
                            @elseif($order->payment_method == 'COD')
                                <p class="small mb-0">Terima kasih. Pembayaran akan dilakukan secara tunai saat kurir mengantar pesananmu.</p>
                            @endif
                        </div>

                        <div class="no-print">
                            @php
                                $waNumber = '6282129626596'; 
                                $waMessage = urlencode('Halo Admin PartKu, saya mau konfirmasi pembayaran untuk pesanan ' . $order->order_number . ' sebesar Rp ' . number_format($order->total_amount, 0, ',', '.') . '. Berikut saya lampirkan bukti transfernya.');
                            @endphp
                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="btn btn-success w-100 fw-bold py-2">
                                <i class="bi bi-whatsapp"></i> Konfirmasi via WhatsApp
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- NOTIFIKASI JIKA SUDAH LUNAS --}}
            @if($order->status === 'paid')
                <div class="alert alert-success text-center fw-bold no-print shadow-sm" style="border-left: 5px solid #198754;">
                    <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i> 
                    Pembayaran Berhasil Diterima!<br>
                    <span class="fw-normal fs-6">Pesanan Anda sedang kami siapkan.</span>
                </div>
            @endif

            {{-- Tombol Navigasi Web (Hilang saat di-print) --}}
            <div class="d-flex gap-2 no-print mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary flex-fill py-2 fw-bold">Kembali ke Home</a>
                <button onclick="window.print()" class="btn btn-dark flex-fill py-2 fw-bold"><i class="bi bi-printer"></i> Cetak Invoice</button>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT TIMER (Hanya berjalan jika status PENDING) --}}
@if($order->status === 'pending')
    @push('scripts')
    <script>
        var countDownDate = new Date("{{ $order->created_at->addHours(24)->toIso8601String() }}").getTime();

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;

            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = hours + "j " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "WAKTU HABIS";
                document.getElementById("countdown").style.color = "#ff0000";
            }
        }, 1000);
    </script>
    @endpush
@endif

@endsection