<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CancelUnpaidOrders extends Command
{
    // Nama perintah yang akan dipanggil oleh scheduler
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Membatalkan pesanan yang belum dibayar setelah 24 jam';

    public function handle()
    {
        // Cari pesanan dengan status 'pending' yang dibuat lebih dari 24 jam yang lalu
        $unpaidOrders = Order::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();

        $count = 0;
        foreach ($unpaidOrders as $order) {
            // Ubah status menjadi 'cancelled'
            $order->status = 'cancelled'; 
            $order->save();
            $count++;
        }

        $this->info("Berhasil membatalkan {$count} pesanan yang sudah kedaluwarsa.");
    }
}