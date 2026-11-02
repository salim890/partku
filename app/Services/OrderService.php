<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function processCheckout(array $data, array $cart)
    {
        // ==========================================
        // FRAUD RISK SCORING (Bab 7.4 - Velocity Check)
        // ==========================================
        if (auth()->check()) {
            $recentOrdersCount = Order::where('user_id', auth()->id())
                                     ->where('created_at', '>=', now()->subMinutes(10))
                                     ->count();
            
            if ($recentOrdersCount >= 3) {
                throw new \Exception("Sistem mendeteksi aktivitas tidak wajar (Terlalu banyak pesanan dalam 10 menit). Silakan coba lagi nanti.");
            }
        }
        // ==========================================
        DB::beginTransaction();
        try {
            $orderNumber = 'PK-' . date('Ymd') . '-' . rand(1000, 9999);
            $totalAmount = 0;

            // 1. Buat Order
            $order = Order::create([
                'order_number'    => $orderNumber,
                'user_id'         => auth()->check() ? auth()->id() : null, 
                'customer_name'   => $data['name'],
                'customer_email'  => $data['email'],
                'customer_phone'  => $data['phone'],
                'customer_address'=> $data['address'],
                'payment_method'  => $data['payment'],
                'total_amount'    => 0, 
                'status'          => 'pending',
                'shipping_method' => explode('|', $data['shipping_method'])[0],
                'shipping_cost'   => $data['shipping_cost'],
            ]);

                        // 2. Simpan Item & Cek Stok (Optimistic Locking)
            foreach ($cart as $productId => $qty) {
                $product = Product::findOrFail($productId);
                $subtotal = $product->price * $qty;
                $totalAmount += $subtotal;

                // OPTIMISTIC LOCKING: Cek stok dan kurangi dalam satu perintah SQL
                $affected = Product::where('id', $productId)
                                   ->where('stock', '>=', $qty)
                                   ->decrement('stock', $qty);

                // Jika affected = 0, berarti stok tidak cukup (ada user lain yang mendahului)
                if ($affected === 0) {
                    throw new \Exception("Stok {$product->name} habis atau tidak mencukupi. Silakan coba lagi.");
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'qty'        => $qty,
                    'price'      => $product->price,
                    'product_name_snapshot' => $product->name,
                    'price_snapshot'        => $product->price,
                ]);
            

                $product->decrement('stock', $qty);
            }

            // 3. Tambah Ongkir & Diskon
            $totalAmount += $data['shipping_cost'];
            if (isset($data['coupon']) && $data['coupon'] === 'PARTKU10') {
                $totalAmount -= ($totalAmount * 0.10);
            }

            $order->update(['total_amount' => $totalAmount]);
            // Simpan Event ke Outbox (Dual-Write Solution)
            \DB::table('outbox_events')->insert([
                'event_type' => 'order.created',
                'payload' => json_encode(['order_id' => $order->id, 'order_number' => $orderNumber]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::commit();

            // 4. Log
            ActivityLog::create([
                'user_id'     => auth()->check() ? auth()->id() : null, 
                'action'      => 'CHECKOUT',
                'description' => 'Melakukan pemesanan ' . $orderNumber, 
                'ip_address'  => request()->ip()
            ]);

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}