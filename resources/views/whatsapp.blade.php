@extends('layouts.app')

@section('title', 'Hubungi Kami via WhatsApp')

@push('styles')
<style>
    body { background-color: #f0f2f5; } /* Warna background khas WhatsApp Web */
    
    .wa-container { 
        max-width: 500px; 
        margin: 50px auto; 
        background: #fff; 
        border-radius: 12px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        padding: 40px 30px; 
        text-align: center; 
    }
    
        .wa-logo { 
        height: 70px; /* Ukuran tinggi logo */
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin: 0 auto 20px; 
    }
    .wa-logo img {
        height: 100%;
        object-fit: contain;
    }

    .brand-name { 
        font-size: 1.5rem; 
        font-weight: 600; 
        color: #333; 
        margin-bottom: 5px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 8px;
    }
    
    .verified-badge { color: #1da1f2; font-size: 1.2rem; }
    
    .phone-number { color: #666; font-size: 0.95rem; margin-bottom: 30px; }

    /* Kotak Pesan Tiruan */
    .chat-bubble { 
        background: #f0f2f5; 
        padding: 15px 20px; 
        border-radius: 10px; 
        color: #444; 
        font-size: 0.95rem; 
        text-align: left; 
        margin-bottom: 30px; 
        border-left: 4px solid #25d366;
    }

    /* Tombol Aksi */
    .btn-wa-app { 
        background: #25d366; 
        color: white; 
        border: none; 
        padding: 12px; 
        border-radius: 30px; 
        font-weight: 600; 
        font-size: 1rem;
        width: 100%;
        margin-bottom: 15px;
        transition: 0.3s;
        display: block;
        text-decoration: none;
    }
    .btn-wa-app:hover { background: #128C7E; color: white; }
    
    .btn-wa-web { 
        background: transparent; 
        color: #333; 
        border: 1px solid #ccc; 
        padding: 12px; 
        border-radius: 30px; 
        font-weight: 600; 
        font-size: 1rem;
        width: 100%;
        transition: 0.3s;
        display: block;
        text-decoration: none;
    }
    .btn-wa-web:hover { background: #f8f9fa; color: #333; }
</style>
@endpush

@section('content')

<div class="container">
    <div class="wa-container">
        
                {{-- Logo & Brand --}}
        <div class="wa-logo">
            <img src="{{ asset('images/honda-logo.png') }}" alt="Honda PartKu">
        </div>
        
        <div class="brand-name">
            PartKu Customer Service 
            <i class="bi bi-patch-check-fill verified-badge"></i>
        </div>
        
        {{-- Nomor WA (Ganti pakai nomor aslimu) --}}
        <div class="phone-number">Ngobrol di WhatsApp dengan +62 821-2962-6596</div>

        {{-- Teks Pesan Otomatis --}}
        <div class="chat-bubble">
            Halo Admin PartKu, saya butuh bantuan terkait sparepart motor Honda. Boleh dibantu?
        </div>

        {{-- Tombol Aksi --}}
        {{-- Nomor WA di bawah ini menggunakan format 62 (tanpa angka 0 di depan) --}}
        @php
            $waNumber = '6282129626596'; 
            $waMessage = urlencode('Halo Admin PartKu, saya butuh bantuan terkait sparepart motor Honda. Boleh dibantu?');
        @endphp

        <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" class="btn-wa-app">
            Buka Aplikasi
        </a>
        
        <a href="https://api.whatsapp.com/send/?phone={{ $waNumber }}&text={{ $waMessage }}" target="_blank" class="btn-wa-web">
            Lanjutkan ke WhatsApp Web
        </a>

        <div class="mt-4 text-muted small">
            <i class="bi bi-whatsapp text-success"></i> Tidak punya aplikasinya? <a href="#" class="text-success text-decoration-none">Unduh sekarang</a>
        </div>

    </div>
</div>

@endsection