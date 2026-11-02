<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1 sampai 5
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Mencegah 1 user ngasih 2 ulasan untuk produk yang sama di 1 order
            $table->unique(['user_id', 'product_id', 'order_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};