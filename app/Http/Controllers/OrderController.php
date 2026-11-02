<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. Halaman Checkout
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('success', 'Keranjang kosong. Silakan pilih produk dulu.');
        }
        return view('checkout');
    }

      public function process(Request $request, \App\Services\OrderService $orderService)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'address' => 'required|min:10',
            'payment' => 'required',
            'terms' => 'accepted',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('cart.index');

        try {
            $order = $orderService->processCheckout($request->all(), $cart);
            session()->forget('cart');
            return redirect()->route('order.success', $order->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // 3. Halaman Sukses / Invoice
    public function success($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('order-sukses', compact('order'));
    }

    // 4. Halaman Form Lacak Pesanan
    public function trackForm()
    {
        return view('lacak-pesanan');
    }

    // 5. Proses Lacak Pesanan (Pakai Email & No Order)
    public function trackResult(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
            'email' => 'required|email'
        ]);
        
        $order = Order::with('items.product')
            ->where('order_number', $request->order_number)
            ->where('customer_email', $request->email)
            ->first();

        if (!$order) {
            return back()->with('error', 'Nomor order atau Email tidak ditemukan. Silakan periksa kembali.');
        }

        return view('lacak-pesanan', compact('order'));
    }
}