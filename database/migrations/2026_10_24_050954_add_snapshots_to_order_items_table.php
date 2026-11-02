<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('order_items', function (Blueprint $table) {
            // Tambahkan ->nullable() agar data lama tidak error
            $table->string('product_name_snapshot')->nullable();
            $table->integer('price_snapshot')->nullable();
        });
    }
    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['product_name_snapshot', 'price_snapshot']);
        });
    }
};