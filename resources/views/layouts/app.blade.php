<!DOCTYPE html>
<html lang="id">
<head>
    {{-- PWA Settings --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#cc0000">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PartKu')</title>
    <meta name="description" content="@yield('meta_description', 'PartKu - Toko Sparepart Original Honda Online Terlengkap. Sparepart Tepat, Konsultasi Gratis.')">
    <meta name="keywords" content="@yield('meta_keywords', 'sparepart honda, oli honda, v-belt, kampas rem')">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔧</text></svg>">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f8f9fa; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; }
        .navbar-brand span { color: #ff3c00; }
        .product-image {
            height: 180px; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;
        }
        .product-card { transition: 0.2s; border: 1px solid #eee; }
        .product-price { color: #ff3c00; font-weight: 700; font-size: 1.1rem; }
        .section-title { font-weight: 700; margin-bottom: 1.5rem; }
        .section-title::after {
            content: ''; display: block; width: 60px; height: 3px;
            background: #ff3c00; margin-top: 8px;
        }
        .btn-primary { background: #ff3c00; border-color: #ff3c00; }
        .btn-primary:hover { background: #d93300; border-color: #d93300; }
        .btn-outline-primary { color: #ff3c00; border-color: #ff3c00; }
        .btn-outline-primary:hover { background: #ff3c00; color: white; }
        footer { background: #111; color: #ccc; padding: 40px 0 20px; margin-top: 60px; }
        footer h5 { color: white; }
        footer a { color: #aaa; text-decoration: none; }
        footer a:hover { color: #ff3c00; }

        /* ========================================= */
        /* CSS ANIMASI (SANGAT RINGAN)               */
        /* ========================================= */
        .animate-on-scroll { 
            opacity: 0; 
            transform: translateY(30px); 
        }
        
        /* Efek Tilt CSS Murni (Tidak membebani CPU) */
        .tilt-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .tilt-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            z-index: 10;
        }

        /* Tombol WhatsApp */
        .wa-float {
            position: fixed; width: 60px; height: 60px; bottom: 30px; right: 30px;
            background-color: #25d366; color: #FFF; border-radius: 50px;
            text-align: center; font-size: 35px; box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
            z-index: 1000; display: flex; align-items: center; justify-content: center;
            text-decoration: none; transition: all 0.3s ease;
        }
        .wa-float:hover { background-color: #128C7E; color: #FFF; transform: scale(1.1); }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ asset('images/honda-logo.png') }}" alt="Honda" style="height: 35px; object-fit: contain;">
            <span style="font-weight: 800; font-size: 1.5rem;">Part<span style="color: #ff3c00;">Ku</span></span>
        </a>
        
        {{-- Tombol Menu (Mobile) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navMenu">
            {{-- Menu Tengah --}}
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('katalog') }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('diagram') }}">Diagram</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('konsultasi') }}">Konsultasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('order.track') }}">Lacak Pesanan</a>
                </li>
            </ul>
            
            {{-- Search & Cart (Kanan) --}}
            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3 mt-3 mt-lg-0">
                <form action="{{ route('katalog') }}" method="GET" class="d-flex m-0">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari sparepart..." value="{{ request('search') }}" style="min-width: 180px;">
                        <button class="btn btn-danger" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            @else
                            <li><a class="dropdown-item" href="{{ route('customer.orders') }}">Pesanan Saya</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><form action="{{ route('logout') }}" method="POST">@csrf<button class="dropdown-item text-danger">Logout</button></form></li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm fw-bold">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-danger btn-sm fw-bold">Daftar</a>
                @endauth

                <a class="btn btn-light position-relative border" href="{{ route('cart.index') }}">
                    <i class="bi bi-cart3 fs-5"></i>
                    @php $cartCount = array_sum(session('cart', [])); @endphp
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="transform: translate(-50%, -20%) !important;">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>

@if (session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/honda-logo.png') }}" alt="Honda" style="height: 30px; object-fit: contain; ">
                    PartKu
                </h5>
                <p class="mt-2">Sparepart Tepat, Konsultasi Gratis.</p>
            </div>
            <div class="col-md-4">
                <h5>Menu Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('kebijakan') }}">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('katalog') }}">Katalog Produk</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Hubungi Kami</h5>
                <p>0812-3456-7890</p>
                <p>halo@partku.id</p>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center mb-0 small">© 2026 PartKu.</p>
    </div>
</footer>

{{-- TOMBOL WHATSAPP MELAYANG --}}
<a href="{{ route('whatsapp') }}" class="wa-float" title="Chat Admin via WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- GSAP Saja (Tanpa Lenis) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        gsap.registerPlugin(ScrollTrigger);

        // Animasi Scroll (Sangat Ringan)
        ScrollTrigger.batch(".animate-on-scroll", {
            onEnter: batch => gsap.to(batch, {
                opacity: 1,
                y: 0,
                stagger: 0.1,
                duration: 0.6,
                ease: "power2.out",
                overwrite: true
            }),
            start: "top 90%",
            once: true // Animasi cuma jalan sekali agar tidak membebani
        });

        // Animasi Hero khusus untuk Home (karena ada #hero-icon)
        if(document.querySelector("#hero-icon")) {
            gsap.to(".hero-section .animate-on-scroll", {
                y: 0, opacity: 1, duration: 0.8, stagger: 0.1, ease: "power3.out", delay: 0.2
            });

            gsap.to("#hero-icon", {
                y: 80,
                ease: "none",
                scrollTrigger: {
                    trigger: ".hero-section",
                    start: "top top",
                    end: "bottom top",
                    scrub: 0.5
                }
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>