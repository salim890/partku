@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

<section class="py-5 text-white" style="background: linear-gradient(135deg, #ff3c00 0%, #b32a00 100%);">
    <div class="container">
        <h1 class="fw-bold"><i class="bi bi-shop"></i> Tentang PartKu</h1>
        <p class="mb-0">Sparepart Tepat, Konsultasi Gratis.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Cerita --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="section-title">Cerita Kami</h3>
                    <p>PartKu lahir dari pengalaman sederhana: sulitnya mencari sparepart original Honda untuk motor matic, dan minimnya tempat bertanya saat motor bermasalah.</p>
                    <p>Berawal dari bengkel kecil di tahun 2024, kami memutuskan untuk membawa pengalaman itu ke ranah online. Misi kami sederhana: <strong>menyediakan sparepart original Honda yang pasti asli, dengan harga yang wajar, dan didampingi konsultasi gratis</strong> untuk setiap pelanggan.</p>
                    <p class="mb-0">Sampai hari ini, PartKu telah melayani ratusan pelanggan di seluruh Indonesia. Kami percaya setiap motor Honda layak mendapatkan sparepart terbaik — bukan yang murah tapi cepat rusak, melainkan yang <strong>tepat</strong>.</p>
                </div>
            </div>

            {{-- Visi Misi --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="text-danger mb-3" style="font-size: 2.5rem;">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                            <h4>Visi</h4>
                            <p class="mb-0">Menjadi toko sparepart motor Honda matic online paling terpercaya di Indonesia, dengan pelayanan konsultasi yang ramah dan solutif.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="text-danger mb-3" style="font-size: 2.5rem;">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <h4>Misi</h4>
                            <ul class="mb-0 ps-3">
                                <li>Menyediakan sparepart original Honda 100% asli.</li>
                                <li>Memberikan konsultasi gratis untuk setiap pelanggan.</li>
                                <li>Menjaga harga tetap wajar & terjangkau.</li>
                                <li>Mengirim pesanan dengan cepat & packing aman.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nilai --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="section-title">Nilai yang Kami Pegang</h3>
                    <div class="row g-4 text-center">
                        <div class="col-md-4">
                            <i class="bi bi-patch-check-fill text-danger" style="font-size: 2.5rem;"></i>
                            <h6 class="mt-2">Keaslian</h6>
                            <p class="text-muted small mb-0">Semua produk dijamin original Honda.</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-chat-heart-fill text-danger" style="font-size: 2.5rem;"></i>
                            <h6 class="mt-2">Pelayanan</h6>
                            <p class="text-muted small mb-0">Konsultasi gratis, tidak ada pertanyaan yang terlalu kecil.</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-hand-thumbs-up-fill text-danger" style="font-size: 2.5rem;"></i>
                            <h6 class="mt-2">Kepercayaan</h6>
                            <p class="text-muted small mb-0">Ratusan pelanggan puas sejak 2024.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('katalog') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-grid"></i> Lihat Katalog Kami
                </a>
            </div>

        </div>
    </div>
</div>

@endsection