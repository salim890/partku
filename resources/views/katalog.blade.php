@extends('layouts.app')

@section('title', 'Katalog Produk')

@push('styles')
<style>
    /* Custom CSS untuk Katalog */
    .filter-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .filter-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
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
</style>
@endpush

@section('content')

{{-- Header Halaman --}}
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">Katalog Sparepart</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0">Katalog Sparepart Honda</h3>
    </div>
</div>

<div class="container pb-5">
    <div class="row">

        {{-- ============ SIDEBAR FILTER ============ --}}
        <div class="col-lg-3 mb-4 animate-on-scroll">
            <div class="card filter-card sticky-top" style="top: 80px; z-index: 10;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-funnel text-danger"></i> Filter Produk</h6>
                    
                    <form method="GET" action="{{ route('katalog') }}">
                        {{-- Pencarian --}}
                        <div class="mb-3">
                            <label class="filter-label">Cari Produk</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Nama / Part Number" value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Model Motor --}}
                        <div class="mb-3">
                            <label class="filter-label">Model Motor</label>
                            <select name="motor" class="form-select form-select-sm">
                                <option value="">Semua Model</option>
                                @foreach ($motors as $motor)
                                    <option value="{{ $motor }}" {{ request('motor') == $motor ? 'selected' : '' }}>Honda {{ $motor }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label class="filter-label">Kategori</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga --}}
                        <div class="mb-4">
                            <label class="filter-label">Rentang Harga</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                                <span class="text-muted">-</span>
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('katalog') }}" class="btn btn-light btn-sm w-100 mt-2 text-muted">
                            Reset Filter
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- ============ GRID PRODUK ============ --}}
        <div class="col-lg-9">
            
            {{-- Top Bar: Info & Sorting --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white p-3 rounded-3 shadow-sm mb-4 animate-on-scroll">
                <div class="mb-2 mb-md-0">
                    Menampilkan <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="text-muted text-nowrap small">Urutkan:</label>
                    <form method="GET" action="{{ route('katalog') }}" id="sortForm">
                        {{-- Pertahankan filter yang sedang aktif --}}
                        @foreach(request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <select name="sort" class="form-select form-select-sm" onchange="document.getElementById('sortForm').submit()" style="width: 180px;">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Daftar Produk --}}
            @if ($products->count() > 0)
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4 animate-on-scroll">
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
                                    
                                    {{-- Rating Dinamis --}}
                                    <div class="d-flex align-items-center gap-1 mb-2" style="font-size: 0.75rem;">
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                                            @endfor
                                        </span>
                                        <span class="text-muted ms-1">{{ $product->review_count }} ulasan</span>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold fs-5 text-danger">{{ $product->formatted_price }}</span>
                                            <small class="text-muted"><i class="bi bi-box-seam"></i> Stok: {{ $product->stock }}</small>
                                        </div>
                                        <a href="{{ route('produk.detail', $product->slug) }}" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-5 d-flex justify-content-center animate-on-scroll">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-3 shadow-sm animate-on-scroll">
                    <i class="bi bi-search text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h4 class="mt-3">Produk Tidak Ditemukan</h4>
                    <p class="text-muted">Maaf, kami tidak menemukan produk yang cocok dengan filter kamu.</p>
                    <a href="{{ route('katalog') }}" class="btn btn-outline-danger mt-2">Hapus Semua Filter</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection