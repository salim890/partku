@extends('layouts.app')

@section('title', 'Kontak')

@section('content')

<section class="py-5 text-white" style="background: linear-gradient(135deg, #ff3c00 0%, #b32a00 100%);">
    <div class="container">
        <h1 class="fw-bold"><i class="bi bi-telephone-fill"></i> Hubungi Kami</h1>
        <p class="mb-0">Ada pertanyaan? Kami siap membantu.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
                <div class="text-success mb-3" style="font-size: 3rem;">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <h4>WhatsApp</h4>
                <p class="text-muted small">Chat langsung, respons cepat di jam kerja.</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success">
                    <i class="bi bi-whatsapp"></i> 0812-3456-7890
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
                <div class="text-danger mb-3" style="font-size: 3rem;">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <h4>Email</h4>
                <p class="text-muted small">Untuk pertanyaan resmi & kerja sama.</p>
                <a href="mailto:halo@partku.id" class="btn btn-danger">
                    <i class="bi bi-envelope"></i> halo@partku.id
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
                <div class="text-primary mb-3" style="font-size: 3rem;">
                    <i class="bi bi-instagram"></i>
                </div>
                <h4>Instagram</h4>
                <p class="text-muted small">Ikuti update produk terbaru kami.</p>
                <a href="https://instagram.com/partku.id" target="_blank" class="btn btn-primary">
                    <i class="bi bi-instagram"></i> @partku.id
                </a>
            </div>
        </div>

    </div>

    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-3">Jam Operasional</h4>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td><i class="bi bi-clock text-danger"></i> Senin - Jumat</td>
                            <td class="text-end fw-bold">08.00 - 20.00 WIB</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-clock text-danger"></i> Sabtu</td>
                            <td class="text-end fw-bold">09.00 - 17.00 WIB</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-clock text-danger"></i> Minggu & Hari Libur</td>
                            <td class="text-end text-muted">Tutup (chat tetap dibalas bertahap)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection