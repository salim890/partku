<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function orders()
    {
        // Ambil pesanan milik user yang sedang login
        $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('customer.orders', compact('orders'));
    }
    public function orderDetail($id)
    {
        // Cari pesanan berdasarkan ID DAN pastikan itu milik user yang sedang login
        $order = Order::with('items.product')->where('user_id', auth()->id())->findOrFail($id);
        
        // Kita pakai ulang halaman invoice (order-sukses) yang sudah kita buat kemarin
        return view('order-sukses', compact('order'));
    }
}