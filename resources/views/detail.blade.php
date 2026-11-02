@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', Str::limit($product->description, 150))
@section('meta_keywords', $product->name . ', ' . $product->part_number . ', ' . $product->category . ', sparepart honda')

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    .product-wrapper { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .main-image-container { width: 100%; height: 400px; display: flex; align-items: center; justify-content: center; position: relative; }
    .main-image-container img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .thumbnail-container { display: flex; justify-content: center; gap: 10px; margin-top: 20px; }
    .thumbnail { width: 70px; height: 70px; border: 1px solid #ddd; padding: 5px; cursor: pointer; border-radius: 4px; }
    .thumbnail.active { border-color: #cc0000; }
    .thumbnail img { width: 100%; height: 100%; object-fit: contain; }
    
    .product-title { font-size: 1.8rem; font-weight: 600; color: #333; margin-bottom: 10px; }
    .rating-stars { color: #ffc107; font-size: 0.9rem; margin-bottom: 15px; }
    .product-price { font-size: 1.8rem; color: #333; font-weight: 600; margin-bottom: 20px; }
    
    .spec-row { display: flex; margin-bottom: 8px; font-size: 0.95rem; }
    .spec-label { width: 150px; color: #666; }
    .spec-value { font-weight: 500; color: #333; }

    .motor-list { list-style: none; padding-left: 0; margin-bottom: 20px; }
    .motor-list li { font-size: 0.95rem; color: #cc0000; margin-bottom: 5px; }
    .motor-list li::before { content: "•"; color: #cc0000; font-weight: bold; display: inline-block; width: 1em; }

    .stock-info { color: #28a745; font-size: 0.95rem; margin-bottom: 15px; }
    .buy-container { display: flex; align-items: stretch; max-width: 400px; }
    .qty-input { width: 80px; text-align: center; border: 1px solid #ddd; border-right: none; font-size: 1.1rem; padding: 10px; outline: none; border-radius: 4px 0 0 4px; }
    .btn-beli { background-color: #cc0000; color: white; font-size: 1.1rem; font-weight: 500; border: none; padding: 10px 40px; flex-grow: 1; border-radius: 0 4px 4px 0; cursor: pointer; }
    
    .custom-tabs { border-bottom: 1px solid #ddd; margin-top: 40px; }
    .custom-tabs .nav-link { border: none; border-bottom: 3px solid transparent; color: #666; font-weight: 500; padding: 15px 30px; }
    .custom-tabs .nav-link.active { border-bottom-color: #cc0000; color: #cc0000; background: transparent; }
    .tab-content { background: #fff; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px; }
</style>
@endpush

@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb" style="font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-decoration-none text-muted">Shop</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    {{-- Kotak Utama Produk --}}
    <div class="product-wrapper animate-on-scroll">
        <div class="row">
            {{-- Kiri: Gambar --}}
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="main-image-container border rounded">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </div>
                <div class="thumbnail-container">
                    <div class="thumbnail active">
                        <img src="{{ $product->image_url }}" alt="Thumb 1">
                    </div>
                </div>
            </div>

            {{-- Kanan: Info --}}
            <div class="col-lg-6 ps-lg-4">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="rating-stars mb-3">
                    <span class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                        @endfor
                    </span>
                    <span class="text-muted ms-1">
                        {{ $product->average_rating > 0 ? $product->average_rating : 'Belum ada rating' }} 
                        ({{ $product->review_count }} ulasan)
                    </span>
                </div>

                <div class="product-price">{{ $product->formatted_price }}</div>

                <hr>

                <div class="spec-row">
                    <div class="spec-label">Nama Resmi Produk</div>
                    <div class="spec-value">{{ $product->name }}</div>
                </div>
                <div class="spec-row">
                    <div class="spec-label">Kode Part</div>
                    <div class="spec-value">{{ $product->part_number }}</div>
                </div>
                <div class="spec-row">
                    <div class="spec-label">Kategori</div>
                    <div class="spec-value">{{ $product->category }}</div>
                </div>

                <div class="mt-4 mb-2 fw-bold text-dark">Produk ini bisa digunakan oleh motor:</div>
                <ul class="motor-list">
                    @foreach ($product->motor_models_array as $motor)
                        <li>Honda {{ $motor }}</li>
                    @endforeach
                </ul>

                <div class="stock-info">stok {{ $product->stock }}</div>

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="buy-container">
                        <input type="number" name="qty" class="qty-input" value="1" min="1" max="{{ $product->stock }}">
                        <button type="submit" class="btn-beli" aria-label="Tambahkan {{ $product->name }} ke keranjang">Beli Sekarang</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab Bawah --}}
        <ul class="nav nav-tabs custom-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="deskripsi-tab" data-bs-toggle="tab" data-bs-target="#deskripsi" type="button">Deskripsi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="ulasan-tab" data-bs-toggle="tab" data-bs-target="#ulasan" type="button">Ulasan Pembeli ({{ $product->review_count }})</button>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            {{-- Tab Deskripsi --}}
            <div class="tab-pane fade show active" id="deskripsi" role="tabpanel">
                <h5 class="mb-3">Detail Produk</h5>
                <p class="text-muted">{{ $product->description }}</p>
                <p class="text-muted mt-3">
                    <strong>Ready Stock.</strong><br>
                    Pengiriman produk jika checkout & dibayar sebelum jam 16.00 WIB akan dikirim di hari yang sama. 
                </p>
            </div>
            
            {{-- Tab Ulasan --}}
            <div class="tab-pane fade" id="ulasan" role="tabpanel">
                @forelse($product->reviews as $rev)
                    <div class="border-bottom mb-3 pb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $rev->user->name }}</strong>
                            <span class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                        </div>
                        <p class="mb-1 text-muted small">{{ $rev->created_at->format('d M Y') }}</p>
                        <p class="mb-0">{{ $rev->comment ?? 'Tidak ada komentar.' }}</p>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- Sering Dibeli Bersama --}}
    @if(isset($recommendations) && $recommendations->count() > 0)
    <div class="mt-5 pt-4 border-top animate-on-scroll">
        <h4 class="fw-bold mb-4"><i class="bi bi-cart-check text-danger"></i> Sering Dibeli Bersama</h4>
        <div class="row g-3">
            @foreach($recommendations as $rec)
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm tilt-card">
                    <div style="height: 150px; padding: 10px; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ $rec->image_url }}" alt="{{ $rec->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <div class="card-body p-2 text-center">
                        <div class="small fw-bold mb-1" style="height: 2.5em; overflow: hidden;">{{ $rec->name }}</div>
                        <div class="text-danger fw-bold small mb-2">{{ $rec->formatted_price }}</div>
                        <a href="{{ route('produk.detail', $rec->slug) }}" class="btn btn-outline-danger btn-sm w-100">Lihat</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- JSON-LD SCHEMA UNTUK MESIN PENCARI / AI --}}
    @php
        $schemaData = [
            "@context" => "https://schema.org/",
            "@type" => "Product",
            "name" => $product->name,
            "image" => $product->image_url,
            "description" => $product->description,
            "sku" => $product->part_number,
            "brand" => [
                "@type" => "Brand",
                "name" => "Honda"
            ],
            "offers" => [
                "@type" => "Offer",
                "url" => url()->current(),
                "priceCurrency" => "IDR",
                "price" => $product->price,
                "availability" => $product->stock > 0 ? "https://schema.org/InStock" : "https://schema.org/OutOfStock"
            ],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => $product->average_rating,
                "reviewCount" => $product->review_count
            ]
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_PRETTY_PRINT) !!}
    </script>

</div>
@endsection