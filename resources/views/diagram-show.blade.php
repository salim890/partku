@extends('layouts.app')

@section('title', 'Diagram Part ' . $motor)

@push('styles')
<style>
    body { background-color: #f4f4f4; }
    
    /* Desain Container Katalog */
    .catalog-container { 
        background: #fff; 
        border: 1px solid #ddd; 
        padding: 20px; 
        border-radius: 8px; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* Tabel Part */
    .table-catalog { font-size: 0.85rem; margin-bottom: 0; }
    .table-catalog th { background: #f8f9fa; font-weight: 600; color: #555; }
    
    /* Baris tabel yang bisa diklik */
    .part-row { transition: 0.2s; cursor: pointer; }
    .part-row:hover { background-color: #fff3f0 !important; }
    .part-row a { text-decoration: none; color: inherit; display: block; }
    .part-row a:hover { color: #cc0000; }
    
    /* Kotak Gambar Kosong */
    .img-placeholder { 
        background: #f9f9f9; border: 2px dashed #ccc; border-radius: 8px; 
        height: 400px; display: flex; align-items: center; justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    {{-- Tombol Pilihan Kategori --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        @foreach(['Mesin', 'CVT', 'Pengereman', 'Kelistrikan'] as $cat)
            <a href="{{ route('diagram.show', [$motor, $cat]) }}" 
               class="btn {{ $category == $cat ? 'btn-danger' : 'btn-outline-danger' }} fw-bold">
                Diagram {{ $cat }}
            </a>
        @endforeach
    </div>

    <div class="row g-4">
        
        {{-- KIRI: Gambar --}}
        <div class="col-lg-7">
            <div class="catalog-container text-center">
                <h5 class="fw-bold mb-4 text-start">Honda {{ $motor }} - {{ $category }}</h5>
                
                @php
                    $imgName = 'diagram-' . strtolower($motor) . '-' . strtolower($category) . '.png';
                    $imgPath = public_path('images/' . $imgName);
                @endphp

                @if(file_exists($imgPath))
                    <img src="{{ asset('images/' . $imgName) }}" class="img-fluid" alt="Diagram">
                @else
                    <div class="img-placeholder">
                        <div class="text-center text-muted">
                            <i class="bi bi-image fs-1 mb-2"></i>
                            <p class="mb-1 fw-bold">Gambar Diagram Belum Tersedia</p>
                            <small>Simpan gambar sebagai: <br><strong class="text-danger">{{ $imgName }}</strong><br> di folder public/images</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- KANAN: Tabel Part (Interaktif) --}}
        <div class="col-lg-5">
            <div class="catalog-container">
                <h6 class="fw-bold mb-3">Daftar Part {{ $category }}</h6>
                
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-catalog table-hover">
                        <thead class="sticky-top">
                            <tr>
                                <th>No. Part</th>
                                <th>Deskripsi</th>
                                <th width="60" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                            <tr class="part-row">
                                {{-- Klik part number untuk lihat detail --}}
                                <td>
                                    <a href="{{ route('produk.detail', $p->slug) }}" class="fw-bold text-danger">
                                        {{ $p->part_number }}
                                    </a>
                                </td>
                                {{-- Klik nama produk untuk lihat detail --}}
                                <td>
                                    <a href="{{ route('produk.detail', $p->slug) }}">
                                        {{ $p->name }}<br>
                                        <span class="text-danger fw-bold">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                                    </a>
                                </td>
                                {{-- Tombol Tambah ke Keranjang (Biar nggak bentrok dengan klik baris) --}}
                                <td class="text-center">
                                    <form action="{{ route('cart.add', $p->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Tambah ke Keranjang">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2 opacity-50"></i>
                                    Belum ada produk di kategori ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection