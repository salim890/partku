<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PartKu - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; overflow-x: hidden; }
        
        /* Desain Sidebar */
        .sidebar { width: 260px; height: 100vh; position: fixed; left: 0; top: 0; background: #1a1a1a; color: white; padding-top: 20px; z-index: 1000; }
        .sidebar-brand { font-size: 1.5rem; font-weight: 800; text-align: center; margin-bottom: 30px; color: white; text-decoration: none; display: block;}
        .sidebar-brand span { color: #ff3c00; }
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu li a { color: #aaa; text-decoration: none; display: block; padding: 15px 25px; font-weight: 500; transition: 0.2s; border-left: 3px solid transparent; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background: #2a2a2a; color: white; border-left-color: #ff3c00; }
        .sidebar-menu li a i { margin-right: 10px; font-size: 1.1rem; }
        
        /* Konten Utama */
        .main-content { margin-left: 260px; padding: 30px; min-height: 100vh; }
        .topbar { background: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
    </style>
    @stack('styles')
        {{-- Library Chart.js untuk Grafik --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    {{-- SIDEBAR ADMIN --}}
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-shield-lock-fill text-danger"></i> Admin<span>Ku</span>
        </a>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> Kelola Produk</a></li>
            <li><a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs') ? 'active' : '' }}"><i class="bi bi-list-check"></i> Log Aktivitas</a></li>
            <li><hr class="border-secondary mx-3"></li>
            <li><a href="{{ route('home') }}"><i class="bi bi-house-door-fill"></i> Ke Halaman Toko</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-link text-start text-decoration-none w-100" style="color:#aaa; padding: 15px 25px;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    {{-- MAIN CONTENT ADMIN --}}
    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0 fw-bold">@yield('title')</h5>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Halo, <strong>{{ auth()->user()->name }}</strong></span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>