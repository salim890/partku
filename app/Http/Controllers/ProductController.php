<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('part_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Motor
        if ($request->filled('motor')) {
            $query->where('motor_models', 'like', '%' . $request->motor . '%');
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // FITUR BARU: Sorting (Urutkan)
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $motors = ['Beat', 'Vario', 'PCX', 'Scoopy', 'Genio', 'ADV'];
        $categories = ['Mesin', 'CVT', 'Pengereman', 'Kelistrikan'];

        return view('katalog', compact('products', 'motors', 'categories'));
    }

        public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        // 1. Produk Terkait (Kategori Sama)
        $related = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // 2. Sering Dibeli Bersama (Berdasarkan riwayat order)
        $orderIds = \DB::table('order_items')->where('product_id', $product->id)->pluck('order_id');
        
        $boughtTogether = \DB::table('order_items')
            ->whereIn('order_id', $orderIds)
            ->where('product_id', '!=', $product->id)
            ->select('product_id', \DB::raw('COUNT(*) as total'))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(4)
            ->pluck('product_id');
            
        $recommendations = Product::whereIn('id', $boughtTogether)->get();

        return view('detail', compact('product', 'related', 'recommendations'));
    }
}