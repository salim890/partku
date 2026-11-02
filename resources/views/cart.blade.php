@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    
    .page-title { font-size: 1.5rem; font-weight: 600; color: #333; margin-bottom: 20px; }
    
    /* Kotak Putih Utama */
    .cart-wrapper { background: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 20px; }
    
    /* Desain Tabel */
    .cart-table th { border-top: none; border-bottom: 2px solid #eee; color: #666; font-weight: 500; font-size: 0.9rem; padding-bottom: 15px; }
    .cart-table td { vertical-align: middle; border-bottom: 1px solid #eee; padding: 20px 10px; }
    
    /* Info Produk di Tabel */
    .product-img-cart { width: 80px; height: 80px; object-fit: contain; border: 1px solid #eee; padding: 5px; border-radius: 4px; background: #fff; }
    .product-name-cart { font-size: 1rem; font-weight: 500; color: #333; text-decoration: none; display: block; margin-bottom: 5px; line-height: 1.4; }
    .product-name-cart:hover { color: #cc0000; }
    .product-part-cart { font-size: 0.8rem; color: #888; }
    
    /* Input Jumlah */
    .qty-input-cart { 
        width: 60px; text-align: center; border: 1px solid #ddd; 
        padding: 5px; font-size: 0.9rem; border-radius: 4px;
    }
    
    /* Harga */
    .price-text { font-weight: 600; color: #333; }
    
    /* Tombol Hapus */
    .btn-remove { color: #dc3545; background: none; border: none; font-size: 1.2rem; transition: 0.2s; cursor: pointer; }
    .btn-remove:hover { color: #a71d2a; }

    /* Kotak Ringkasan */
    .summary-box { background: #fff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); padding: 25px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 0.95rem; color: #666; }
    .summary-total { font-size: 1.3rem; font-weight: 700; color: #cc0000; border-top: 2px solid #eee; padding-top: 15px; margin-bottom: 20px; }
    
    .btn-checkout { 
        background-color: #cc0000; color: white; border: none; padding: 15px; 
        width: 100%; font-size: 1.1rem; font-weight: 600; border-radius: 4px; transition: 0.2s;
    }
    .btn-checkout:hover { background-color: #a30000; }
</style>
@endpush

@section('content')

<div class="container py-5">
    <h1 class="page-title">Keranjang Belanja</h1>

    @if (count($items) > 0)
        <div class="row g-4">
            
            {{-- KIRI: Daftar Produk --}}
            <div class="col-lg-8">
                <div class="cart-wrapper">
                    <table class="table cart-table mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center" width="120">Harga</th>
                                <th class="text-center" width="100">Jumlah</th>
                                <th class="text-end" width="120">Subtotal</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item['product']->image_url }}" class="product-img-cart" alt="{{ $item['product']->name }}">
                                            <div>
                                                <a href="{{ route('produk.detail', $item['product']->slug) }}" class="product-name-cart">
                                                    {{ $item['product']->name }}
                                                </a>
                                                <div class="product-part-cart">Kode: {{ $item['product']->part_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center price-text">
                                        {{ $item['product']->formatted_price }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.update', $item['product']->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="qty" value="{{ $item['qty'] }}" 
                                                   min="1" max="{{ $item['product']->stock }}" 
                                                   class="qty-input-cart" 
                                                   onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="text-end price-text">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-remove" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('katalog') }}" class="btn btn-outline-secondary px-4" style="border-radius: 4px;">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Yakin mau kosongkan keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-link text-danger text-decoration-none">
                            <i class="bi bi-trash"></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>

            {{-- KANAN: Ringkasan Belanja --}}
            <div class="col-lg-4">
                <div class="summary-box">
                    <h5 class="mb-4 fw-bold">Ringkasan Belanja</h5>
                    
                    <div class="summary-row">
                        <span>Total Item</span>
                        <span class="fw-bold text-dark">{{ array_sum(array_column($items, 'qty')) }} pcs</span>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-total d-flex justify-content-between">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn-checkout d-block text-center text-decoration-none">
                        Checkout
                    </a>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted"><i class="bi bi-shield-check"></i> Transaksi Aman & Terpercaya</small>
                    </div>
                </div>
            </div>

        </div>
    @else
        {{-- Tampilan Keranjang Kosong --}}
        <div class="cart-wrapper text-center py-5">
            <i class="bi bi-cart-x text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
            <h4 class="mt-4 fw-bold">Keranjang Kamu Kosong</h4>
            <p class="text-muted mb-4">Yuk, cari sparepart original Honda untuk motormu!</p>
            <a href="{{ route('katalog') }}" class="btn btn-danger px-4 py-2" style="border-radius: 4px;">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

@endsection