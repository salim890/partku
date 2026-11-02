<?php
namespace App\Http\Controllers;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $order_id, $product_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        // Pastikan order ini milik user yang login dan statusnya 'selesai'
        $order = Order::where('id', $order_id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($order->status !== 'selesai') {
            return back()->with('error', 'Anda hanya bisa memberi ulasan setelah pesanan selesai.');
        }

        // Simpan ulasan
        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product_id,
            'order_id' => $order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}