<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel produk utama
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Nama varian, misal: "0.8L", "0.65L", "Warna Merah"
            $table->string('name'); 
            // Harga khusus untuk varian ini
            $table->integer('price'); 
            // Stok khusus untuk varian ini
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_variants');
    }
};