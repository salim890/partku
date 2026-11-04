<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('order_items', function (Blueprint $table) {
            // Boleh kosong (nullable) untuk jaga-jaga kalau ada produk tanpa varian
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
        });
    }
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};