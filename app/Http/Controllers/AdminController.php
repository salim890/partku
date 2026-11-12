<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Enums\OrderStatus;

class AdminController extends Controller
{
        public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        // 1. Data Pesanan
        $orders = Order::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        
        // 2. Metrik Bisnis
        $validOrders = Order::whereIn('status', ['dikirim', 'selesai'])->get();
        $totalRevenue = $validOrders->sum('total_amount');
        $totalOrders = $validOrders->count();
        $aov = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalItemsSold = OrderItem::whereHas('order', function($q) {
            $q->whereIn('status', ['dikirim', 'selesai']);
        })->sum('qty');

        // 3. Data Grafik Penjualan (7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            // Hitung total pendapatan di hari itu
            $dailyRevenue = Order::whereDate('created_at', $date)
                                 ->whereIn('status', ['dikirim', 'selesai'])
                                 ->sum('total_amount');
            $chartData[] = $dailyRevenue;
        }

        // 4. Data Semua User (Untuk fitur blokir/hapus)
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'orders', 'totalRevenue', 'totalOrders', 'aov', 
            'pendingOrders', 'totalCustomers', 'totalProducts', 'totalItemsSold',
            'chartLabels', 'chartData', 'users'
        ));
    }

    // Fungsi Hapus User (Khusus Admin)
    public function deleteUser($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }
        
        $user->delete();
        return back()->with('success', 'Akun pengguna berhasil dihapus.');
    }

    // METHOD BARU: Halaman Laporan dengan Search & Filter
    public function report(Request $request, $type)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $data = [];
        $title = '';

        // Logic berdasarkan tombol yang diklik
        if ($type == 'revenue' || $type == 'orders') {
            $title = $type == 'revenue' ? 'Laporan Pendapatan (Pesanan Sukses)' : 'Semua Pesanan';
            $query = $type == 'revenue' ? Order::whereIn('status', ['dikirim', 'selesai']) : Order::query();
            
            // Search Bar
            if ($request->filled('search')) {
                $query->where('order_number', 'like', '%' . $request->search . '%')
                      ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            }
            // Filter Tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
            }

            $data = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        } elseif ($type == 'pending') {
            $title = 'Pesanan Menunggu Pembayaran (Pending)';
            $query = Order::where('status', 'pending');
            if ($request->filled('search')) {
                $query->where('order_number', 'like', '%' . $request->search . '%');
            }
            $data = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        } elseif ($type == 'customers') {
            $title = 'Daftar Pelanggan Terdaftar';
            $query = User::where('role', 'customer');
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            }
            $data = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        } elseif ($type == 'products') {
            $title = 'Daftar Produk Terjual';
            // Ambil produk yang pernah terjual
            $query = OrderItem::with('product', 'order')
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['dikirim', 'selesai']);
                });
            
            if ($request->filled('search')) {
                $query->whereHas('product', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }
            $data = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        }

        return view('admin.report', compact('data', 'title', 'type'));
    }

    public function logs()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->take(100)->get();
        return view('admin.logs', compact('logs'));
    }

            public function updateStatus(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $order = Order::findOrFail($id);
        
        // Simpan status lama untuk audit log
        $oldStatus = $order->status;

        // 1. Ambil status saat ini dan status yang mau diubah
        $currentStatus = \App\Enums\OrderStatus::from($order->status);
        $newStatus = \App\Enums\OrderStatus::from($request->status);

        // 2. Cek apakah transisinya valid (Aturan State Machine)
        if (!$currentStatus->canTransitionTo($newStatus)) {
            return back()->with('error', 'Gagal! Anda tidak bisa melompati status. Transisi status tidak valid.');
        }
        
        // 3. Kalau valid, simpan ke database
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number
        ]);

        // ==============================================================
        // TAMBAHAN BAB 6: AUDIT TRAIL & JURNAL AKUNTANSI
        // ==============================================================

        // A. Catat Log Status (Audit Trail - Bab 6.1.3)
        \DB::table('order_status_logs')->insert([
            'order_id' => $order->id,
            'from_status' => $oldStatus,
            'to_status' => $request->status,
            'user_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // B. Integrasi Jurnal SIA (Bab 6.6.3)
        // Hanya buat jurnal jika status berubah MENJADI 'paid' (lunas)
        if ($request->status === 'paid' && $oldStatus !== 'paid') {
            // Double-entry: Debit Piutang, Kredit Penjualan
            \DB::table('journal_entries')->insert([
                [
                    'order_id' => $order->id, 
                    'account_name' => 'Piutang Gateway', 
                    'debit' => $order->total_amount, 
                    'credit' => 0, 
                    'created_at' => now(), 'updated_at' => now()
                ],
                [
                    'order_id' => $order->id, 
                    'account_name' => 'Pendapatan Penjualan', 
                    'debit' => 0, 
                    'credit' => $order->total_amount, 
                    'created_at' => now(), 'updated_at' => now()
                ]
            ]);
        }

        // ==============================================================

        // 4. Catat di Activity Log (Kodingan lama kamu)
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'ADMIN_UPDATE',
            'description' => 'Admin mengubah status pesanan ' . $order->order_number . ' menjadi ' . strtoupper($request->status),
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function showInvoice($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $order = Order::with('items.product')->findOrFail($id);
        return view('order-sukses', compact('order'));
    }
}