<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTE PUBLIK (Bisa diakses tanpa login)
// ==========================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog & Detail
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('produk.detail');

// Keranjang (Bebas diakses)
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{id}', [CartController::class, 'add'])->name('cart.add');
Route::put('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

// CHECKOUT (Bisa Guest / Tanpa Login)
Route::get('/checkout', [OrderController::class, 'index'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'process'])->name('checkout.process');
Route::get('/order-sukses/{id}', [OrderController::class, 'success'])->name('order.success');

// Lacak Pesanan (Tanpa Login)
Route::get('/lacak-pesanan', [OrderController::class, 'trackForm'])->name('order.track');
Route::post('/lacak-pesanan', [OrderController::class, 'trackResult'])->name('order.track.result');

// Diagram & Konsultasi
Route::get('/diagram', [DiagramController::class, 'index'])->name('diagram');
Route::get('/diagram/{motor}/{category}', [DiagramController::class, 'show'])->name('diagram.show');
Route::get('/konsultasi', [ConsultationController::class, 'index'])->name('konsultasi');
Route::post('/konsultasi', [ConsultationController::class, 'send'])->name('konsultasi.send');
Route::delete('/konsultasi/hapus', [ConsultationController::class, 'clear'])->name('konsultasi.clear');

// Halaman Statis (Tentang, Kontak, Kebijakan)
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::get('/kebijakan-privasi', [PageController::class, 'kebijakan'])->name('kebijakan');
Route::get('/whatsapp', [PageController::class, 'whatsapp'])->name('whatsapp');

// Auth (Login & Register)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Rate Limit
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// RUTE PRIVAT (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Pembeli
    Route::get('/pesanan-saya', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/pesanan-saya/{id}', [CustomerController::class, 'orderDetail'])->name('customer.order.detail');
    Route::post('/review/{order_id}/{product_id}', [ReviewController::class, 'store'])->name('review.store');

    // Admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::put('/admin/order/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.order.status');
    Route::get('/admin/order/{id}/invoice', [AdminController::class, 'showInvoice'])->name('admin.order.invoice');
    Route::get('/admin/report/{type}', [AdminController::class, 'report'])->name('admin.report');

    // Admin CRUD Produk
    Route::resource('/admin/products', AdminProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

// RUTE API (Tanpa Login)
Route::get('/api/v1/products', [App\Http\Controllers\ApiController::class, 'products']);
});

// Webhook Route (Tanpa CSRF & Tanpa Auth)
Route::post('/api/webhook/payment', [App\Http\Controllers\WebhookController::class, 'handlePayment']);

Route::post('/api/webhook/logistics', [App\Http\Controllers\WebhookController::class, 'handleLogistics']);