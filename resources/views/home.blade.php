@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<style>
    /* Custom CSS untuk Home */
    .hero-section {
        background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
        color: white;
        padding: 80px 0;
        border-radius: 0 0 30px 30px;
        position: relative;
        overflow: hidden;
    }
    .hero-section::after {
        content: '🔧';
        position: absolute;
        right: -20px;
        bottom: -50px;
        font-size: 300px;
        opacity: 0.05;
        transform: rotate(-15deg);
    }
    
    /* Card Kategori */
    .category-card {
        border: 1px solid #eee;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        background: #fff;
    }
    .category-card:hover {
        border-color: #ff3c00;
        box-shadow: 0 10px 20px rgba(255, 60, 0, 0.1);
        transform: translateY(-5px);
    }

    /* Card Produk */
    .product-card {
        border: 1px solid #eee;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }
    .product-title {
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.4;
        height: 2.8em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .part-number {
        font-size: 0.75rem;
        color: #888;
        background: #f1f3f5;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 8px;
    }
    
    /* Banner Promo */
    .promo-banner {
        background: linear-gradient(90deg, #cc0000 0%, #ff3c00 100%);
        border-radius: 12px;
        color: white;
        padding: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(204,0,0,0.2);
    }
    .promo-banner::before {
        content: '\F26E';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: 20px;
        bottom: -20px;
        font-size: 150px;
        opacity: 0.2;
        transform: rotate(-10deg);
        pointer-events: none;
    }
</style>
@endpush

@section('content')

{{-- ============ HERO BANNER ============ --}}
<section class="hero-section text-center text-lg-start">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill animate-on-scroll">🔧 Sparepart Original Honda</span>
                <h1 class="display-4 fw-bold mb-3 animate-on-scroll" style="line-height: 1.2;">Sparepart Tepat,<br>Konsultasi Gratis.</h1>
                <p class="fs-5 mb-4 text-light opacity-75 animate-on-scroll">Temukan sparepart original Honda untuk motor matic kesayanganmu. Bingung pilih part? Chat admin kami — gratis!</p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start animate-on-scroll">
                    <a href="{{ route('katalog') }}" class="btn btn-danger btn-lg px-4 fw-bold shadow">
                        <i class="bi bi-grid"></i> Lihat Katalog
                    </a>
                    <a href="{{ route('konsultasi') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-chat-dots"></i> Konsultasi
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-scooter text-danger animate-on-scroll" id="hero-icon" style="font-size: 15rem; filter: drop-shadow(0 0 30px rgba(255,60,0,0.3));"></i>
            </div>
        </div>
    </div>
</section>

{{-- ============ KATEGORI ============ --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-1 animate-on-scroll">Kategori Produk</h3>
                <p class="text-muted mb-0 animate-on-scroll">Pilih sparepart sesuai kebutuhan motormu</p>
            </div>
            <a href="{{ route('katalog') }}" class="btn btn-outline-danger btn-sm fw-bold animate-on-scroll">Lihat Semua</a>
        </div>
        <div class="row g-4">
            @foreach ($categories as $cat)
                <div class="col-6 col-md-3 animate-on-scroll">
                    <a href="{{ route('katalog', ['category' => $cat['name']]) }}" class="category-card d-block h-100 p-4 text-center text-decoration-none">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; background: {{ $cat['color'] }}15; color: {{ $cat['color'] }};">
                            <i class="bi {{ $cat['icon'] }}" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $cat['name'] }}</h5>
                        <p class="text-muted small mb-0">{{ $cat['desc'] }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ PRODUK POPULER ============ --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-1 animate-on-scroll">Produk Populer</h3>
                <p class="text-muted mb-0 animate-on-scroll">Paling banyak dicari pelanggan PartKu</p>
            </div>
            <a href="{{ route('katalog') }}" class="btn btn-outline-danger btn-sm fw-bold animate-on-scroll">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @forelse ($popularProducts as $product)
                <div class="col-6 col-md-4 col-lg-3 animate-on-scroll">
                    <div class="card product-card h-100 tilt-card">
                        {{-- Gambar --}}
                        <div style="height: 220px; width: 100%; background: #fff; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 20px;">
                            <img src="{{ $product->image_url }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="{{ $product->name }}">
                        </div>
                        
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="badge bg-light text-danger border border-danger mb-2 align-self-start" style="font-size: 0.7rem;">{{ $product->category }}</span>
                            
                            <a href="{{ route('produk.detail', $product->slug) }}" class="text-decoration-none text-dark">
                                <div class="product-title mb-2">{{ $product->name }}</div>
                            </a>
                            
                            <div class="part-number">{{ $product->part_number }}</div>

                            <div class="d-flex align-items-center gap-1 mb-2" style="font-size: 0.75rem;">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                                <span class="text-muted ms-1">{{ $product->review_count }} ulasan</span>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-end mb-2">
                                    <span class="fw-bold fs-5 text-danger">{{ $product->formatted_price }}</span>
                                </div>
                                <div class="text-success small fw-bold mb-2"><i class="bi bi-check-circle-fill"></i> READY STOK</div>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold">
                                        <i class="bi bi-cart-plus"></i> Tambah
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted">Belum ada produk populer.</p></div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============ BANNER PROMO ============ --}}
<section class="py-4 bg-white">
    <div class="container">
        <div class="promo-banner animate-on-scroll">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">Sparepart Original,<br>Bebas Palsu!</h2>
                    <p class="mb-0 opacity-75">Dapatkan jaminan keaslian 100% untuk setiap produk. Gratis konsultasi sebelum beli.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('katalog') }}" class="btn btn-light fw-bold px-4 py-2 text-danger">Belanja Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ PRODUK TERMURAH ============ --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-1 animate-on-scroll">Produk Termurah</h3>
                <p class="text-muted mb-0 animate-on-scroll">Sparepart berkualitas dengan harga terbaik</p>
            </div>
            <a href="{{ route('katalog', ['sort' => 'price_low']) }}" class="btn btn-outline-danger btn-sm fw-bold animate-on-scroll">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @foreach ($cheapestProducts as $product)
                <div class="col-6 col-md-3 animate-on-scroll">
                    <div class="card product-card h-100 tilt-card">
                        <div style="height: 200px; width: 100%; background: #fff; border-bottom: 1px solid #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 20px;">
                            <img src="{{ $product->image_url }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" alt="{{ $product->name }}">
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <a href="{{ route('produk.detail', $product->slug) }}" class="text-decoration-none text-dark">
                                <div class="product-title mb-2">{{ $product->name }}</div>
                            </a>
                            <div class="mt-auto">
                                <div class="fw-bold text-danger mb-1">{{ $product->formatted_price }}</div>
                                <a href="{{ route('produk.detail', $product->slug) }}" class="btn btn-outline-danger btn-sm w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ KEUNGGULAN ============ --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4 animate-on-scroll">
                <div class="bg-light p-4 rounded-3 h-100 border">
                    <i class="bi bi-patch-check-fill text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 fw-bold">100% Original</h5>
                    <p class="text-muted mb-0">Semua produk bergaransi resmi Honda Genuine Parts.</p>
                </div>
            </div>
            <div class="col-md-4 animate-on-scroll">
                <div class="bg-light p-4 rounded-3 h-100 border">
                    <i class="bi bi-chat-heart-fill text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 fw-bold">Konsultasi Gratis</h5>
                    <p class="text-muted mb-0">Bingung diagnosa kerusakan? Chat admin kami, gratis!</p>
                </div>
            </div>
            <div class="col-md-4 animate-on-scroll">
                <div class="bg-light p-4 rounded-3 h-100 border">
                    <i class="bi bi-truck text-danger" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 fw-bold">Kirim Cepat</h5>
                    <p class="text-muted mb-0">Pengiriman ke seluruh Indonesia dengan packing aman.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ MODAL PROMO VOUCHER ============ --}}
<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 text-white text-center pb-4" style="background: linear-gradient(135deg, #cc0000 0%, #ff3c00 100%);">
                <div class="w-100 mt-3">
                    <i class="bi bi-ticket-perforated-fill" style="font-size: 4rem;"></i>
                    <h3 class="fw-bold mt-2 mb-1">Promo Spesial!</h3>
                    <p class="mb-0 opacity-75">Diskon 10% untuk semua sparepart</p>
                </div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted mb-3">Gunakan kode voucher di bawah ini pada halaman Checkout:</p>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                    <div class="border border-2 border-danger border-dashed rounded px-4 py-2 bg-light">
                        <h2 class="fw-bold text-danger mb-0" style="letter-spacing: 3px;">PARTKU10</h2>
                    </div>
                </div>
                <p class="small text-muted mb-4">*Minimal belanja Rp 0. Berlaku untuk semua produk.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('katalog') }}" class="btn btn-danger btn-lg fw-bold">Pakai Voucher & Belanja</a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Memunculkan Popup Promo setelah 1.5 detik halaman dibuka
        setTimeout(function() {
            var promoElement = document.getElementById('promoModal');
            if (promoElement) {
                var myModal = new bootstrap.Modal(promoElement);
                myModal.show();
            }
        }, 1500); 
    });
</script>
@endpush

@endsection