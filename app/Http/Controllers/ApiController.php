<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Endpoint untuk mendapatkan semua produk (JSON)
    public function products()
    {
        $products = Product::all()->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'part_number' => $p->part_number,
                'price' => $p->price,
                'stock' => $p->stock,
                'category' => $p->category,
                'image_url' => $p->image_url,
            ];
        });

        // Kembalikan dalam format JSON (Standar API)
        return response()->json([
            'status' => 'success',
            'message' => 'Data produk berhasil diambil',
            'data' => $products
        ], 200);
    }
}