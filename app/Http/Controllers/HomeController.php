<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // <-- Tambahkan ini

class HomeController extends Controller
{
    public function index()
    {
        // Cache data selama 60 menit (3600 detik)
        $popularProducts = Cache::remember('popular_products', 3600, function () {
            return Product::where('is_popular', true)->take(8)->get();
        });

        $cheapestProducts = Cache::remember('cheapest_products', 3600, function () {
            return Product::orderBy('price', 'asc')->take(4)->get();
        });

        $categories = [
            ['name' => 'Mesin',        'icon' => 'bi-gear-fill',        'color' => '#0d6efd', 'desc' => 'Oli, Busi, Filter'],
            ['name' => 'CVT',          'icon' => 'bi-arrow-repeat',      'color' => '#198754', 'desc' => 'V-Belt, Roller, Kampas Ganda'],
            ['name' => 'Pengereman',   'icon' => 'bi-circle',            'color' => '#dc3545', 'desc' => 'Kampas Rem Depan & Belakang'],
            ['name' => 'Kelistrikan',  'icon' => 'bi-lightning-charge-fill', 'color' => '#ffc107', 'desc' => 'Aki, Lampu, Sein'],
        ];

        return view('home', compact('popularProducts', 'cheapestProducts', 'categories'));
    }
}