<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache; // <-- Tambahan untuk fitur Caching

class AdminProductController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.products.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'name' => 'required',
            'part_number' => 'required|unique:products,part_number',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(100, 999),
            'part_number' => $request->part_number,
            'category' => $request->category,
            'motor_models' => $request->motor_models,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'is_popular' => $request->has('is_popular') ? true : false,
        ]);

        // Logika Upload Gambar (Disimpan dengan nama Part Number)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->part_number . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
        }

        // Hapus Cache Home agar Produk Baru Langsung Muncul
        Cache::forget('popular_products');
        Cache::forget('cheapest_products');

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $product = Product::findOrFail($id);
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'part_number' => 'required|unique:products,part_number,' . $id,
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $product->update([
            'name' => $request->name,
            'part_number' => $request->part_number,
            'category' => $request->category,
            'motor_models' => $request->motor_models,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'is_popular' => $request->has('is_popular') ? true : false,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->part_number . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
        }

        // Hapus Cache Home agar Perubahan Produk Langsung Muncul
        Cache::forget('popular_products');
        Cache::forget('cheapest_products');

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $product = Product::findOrFail($id);
        $product->delete();
        
        // Hapus Cache Home
        Cache::forget('popular_products');
        Cache::forget('cheapest_products');

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}