<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $key => $data) {
            $product = Product::find($data['product_id']);
            if ($product) {
                $subtotal = $data['price'] * $data['qty'];
                
                // Ambil info varian jika ada
                $variantName = null;
                if (!empty($data['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($data['variant_id']);
                    if ($variant) $variantName = $variant->name;
                }

                $items[] = [
                    'key'          => $key, // Kunci unik (id atau id_varian)
                    'product'      => $product,
                    'qty'          => $data['qty'],
                    'price'        => $data['price'],
                    'subtotal'     => $subtotal,
                    'variant_name' => $variantName,
                ];
                $total += $subtotal;
            }
        }

        return view('cart', compact('items', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $qty = (int) $request->input('qty', 1);
        $variantId = $request->input('variant_id');
        
        $price = $product->price; // Harga default

        // Jika produk punya varian
        if ($variantId) {
            $variant = \App\Models\ProductVariant::findOrFail($variantId);
            $price = $variant->price;
            $key = $id . '_' . $variantId; // Kunci unik: misal 1_2
        } else {
            $key = (string) $id; // Kunci unik: misal 1
        }

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $id,
                'variant_id' => $variantId,
                'qty'        => $qty,
                'price'      => $price
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        $qty = (int) $request->input('qty', 1);

        if ($qty < 1) {
            unset($cart[$key]);
        } else {
            if(isset($cart[$key])) {
                $cart[$key]['qty'] = $qty;
            }
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}