@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    .page-title { font-size: 1.5rem; font-weight: 600; color: #333; margin-bottom: 20px; }
    .checkout-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 25px; margin-bottom: 20px; }
    .checkout-card h5 { font-size: 1.1rem; font-weight: 600; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
    
    .form-label { font-size: 0.9rem; font-weight: 500; color: #555; }
    .form-control, .form-select { border-radius: 4px; border: 1px solid #ddd; padding: 10px 15px; font-size: 0.95rem; }
    .form-control:focus, .form-select:focus { border-color: #cc0000; box-shadow: 0 0 0 0.2rem rgba(204, 0, 0, 0.1); }
    
    .payment-option { border: 1px solid #ddd; border-radius: 4px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: 0.2s; }
    .payment-option:hover { border-color: #cc0000; background: #fffcfc; }
    .form-check-input:checked + .form-check-label { font-weight: bold; color: #cc0000; }

    .summary-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 25px; position: sticky; top: 20px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 0.95rem; color: #666; }
    .summary-total { font-size: 1.3rem; font-weight: 700; color: #cc0000; border-top: 2px solid #eee; padding-top: 15px; margin-bottom: 20px; }
    
    .btn-checkout { background-color: #cc0000; color: white; border: none; padding: 15px; width: 100%; font-size: 1.1rem; font-weight: 600; border-radius: 4px; transition: 0.2s; }
    .btn-checkout:hover { background-color: #a30000; }
</style>
@endpush

@section('content')

<div class="container py-5">
    <h1 class="page-title">Checkout Pesanan</h1>

    {{-- PROGRESS INDICATOR --}}
    <div class="d-flex justify-content-center mb-4 mt-2">
        <div class="d-flex align-items-center text-muted">
            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 30px; height: 30px;">1</div>
            <span class="fw-bold text-danger d-none d-md-inline">Informasi & Pengiriman</span>
            <div class="mx-3 border-top border-2" style="width: 40px; border-color: #ddd !important;"></div>
            
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 30px; height: 30px;">2</div>
            <span class="d-none d-md-inline">Pembayaran</span>
            <div class="mx-3 border-top border-2" style="width: 40px; border-color: #ddd !important;"></div>
            
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 30px; height: 30px;">3</div>
            <span class="d-none d-md-inline">Selesai</span>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            
            {{-- KIRI: Data Pembeli & Pengiriman --}}
            <div class="col-lg-7">
                
                <div class="checkout-card">
                    <h5><i class="bi bi-person-lines-fill text-danger me-2"></i> Informasi Pengiriman</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="Contoh: budi@gmail.com" required>
                        <small class="text-muted">Kami akan mengirimkan bukti pesanan ke email ini.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" id="address_input" onkeyup="updateTotal()" rows="3" class="form-control" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" required>{{ old('address') }}</textarea>
                    </div>

                    {{-- PILIHAN EKSPEDISI --}}
                    <div class="mb-3">
                        <label class="form-label">Metode Pengiriman <span class="text-danger">*</span></label>
                        <select name="shipping_method" id="shipping_method" class="form-select" required onchange="updateTotal()">
                            <option value="Ambil di Tempat|0">Ambil di Tempat / COD (Rp 0)</option>
                            <option value="JNE Reguler|15000">JNE Reguler (Rp 15.000)</option>
                            <option value="J&T Express|18000">J&T Express (Rp 18.000)</option>
                            <option value="SiCepat|20000">SiCepat (Rp 20.000)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="Contoh: Tolong dibungkus bubble wrap">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="checkout-card">
                    <h5><i class="bi bi-wallet2 text-danger me-2"></i> Metode Pembayaran</h5>
                    
                    <label class="payment-option d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold text-danger">QRIS (Semua E-Wallet & M-Banking)</div>
                            <small class="text-muted">Scan QR Code otomatis</small>
                        </div>
                        <input class="form-check-input" type="radio" name="payment" value="QRIS" required checked>
                    </label>

                    <label class="payment-option d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold">Transfer Bank BCA</div>
                            <small class="text-muted">No. Rek: <strong>1343263515</strong> a/n Salim</small>
                        </div>
                        <input class="form-check-input" type="radio" name="payment" value="Transfer Bank BCA">
                    </label>

                    <label class="payment-option d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold">Transfer Bank BSI</div>
                            <small class="text-muted">No. Rek: <strong>7186446067</strong> a/n Salim</small>
                        </div>
                        <input class="form-check-input" type="radio" name="payment" value="Transfer Bank BSI">
                    </label>

                    <label class="payment-option d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-bold">Transfer Bank Seabank</div>
                            <small class="text-muted">No. Rek: <strong>901020196918</strong> a/n Salim</small>
                        </div>
                        <input class="form-check-input" type="radio" name="payment" value="Transfer Bank Seabank">
                    </label>

                    <label class="payment-option d-flex align-items-center justify-content-between mb-0">
                        <div>
                            <div class="fw-bold">DANA / OVO / GoPay</div>
                            <small class="text-muted">No. HP: <strong>082129626596</strong> a/n Salim</small>
                        </div>
                        <input class="form-check-input" type="radio" name="payment" value="E-Wallet">
                    </label>
                </div>

            </div>

            {{-- KANAN: Ringkasan Pesanan --}}
            <div class="col-lg-5">
                <div class="summary-card">
                    <h5 class="mb-4 fw-bold">Ringkasan Pesanan</h5>

                    @php
                        $cart = session('cart', []);
                        $total = 0;
                        $totalItems = 0;
                    @endphp

                    <div style="max-height: 300px; overflow-y: auto;">
                        @foreach ($cart as $id => $qty)
                            @php
                                $p = \App\Models\Product::find($id);
                                $subtotal = $p ? $p->price * $qty : 0;
                                $total += $subtotal;
                                $totalItems += $qty;
                            @endphp
                            @if ($p)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $p->image_url }}" style="width: 50px; height: 50px; object-fit: contain; border: 1px solid #eee; border-radius: 4px; padding: 4px;" class="me-3">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold" style="font-size: 0.9rem;">{{ Str::limit($p->name, 35) }}</div>
                                        <small class="text-muted">{{ $qty }} x Rp {{ number_format($p->price, 0, ',', '.') }}</small>
                                    </div>
                                    <div class="fw-bold" style="font-size: 0.9rem;">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <hr>

                    {{-- KOLOM VOUCHER (INI YANG TADI HILANG) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Punya Kode Promo?</label>
                        <input type="text" name="coupon" class="form-control" placeholder="Contoh: PARTKU10">
                    </div>

                    <div class="summary-row">
                        <span>Total Item</span>
                        <span class="fw-bold text-dark">{{ $totalItems }} pcs</span>
                    </div>
                    
                    {{-- ONGKIR OTOMATIS --}}
                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span class="fw-bold text-dark" id="shipping_display">Rp 0</span>
                    </div>
                    
                    <div class="summary-total d-flex justify-content-between">
                        <span>Total Bayar</span>
                        <span id="grand_total_display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <input type="hidden" name="total" id="total_input" value="{{ $total }}">
                    <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">

                    {{-- SYARAT & KETENTUAN --}}
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="terms" id="checkout_terms" required>
                        <label class="form-check-label small text-muted" for="checkout_terms">
                            Saya menyetujui <a href="{{ route('kebijakan') }}" target="_blank" class="text-danger">Kebijakan Privasi</a> dan bersedia data saya diproses untuk pesanan ini.
                        </label>
                    </div>

                    <button type="submit" class="btn-checkout">
                        Buat Pesanan <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none text-muted small">
                            <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    function updateTotal() {
        let select = document.getElementById('shipping_method');
        let value = select.value.split('|'); 
        let cost = parseInt(value[1]);
        
        let subtotal = {{ $total }}; 
        let grandTotal = subtotal + cost;
        
        let formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        
        document.getElementById('shipping_display').innerText = formatter.format(cost);
        document.getElementById('grand_total_display').innerText = formatter.format(grandTotal);
        
        document.getElementById('total_input').value = grandTotal;
        document.getElementById('shipping_cost_input').value = cost;
    }
    
    document.addEventListener("DOMContentLoaded", updateTotal);
</script>
@endpush

@endsection