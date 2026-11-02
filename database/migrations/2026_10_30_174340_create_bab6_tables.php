<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Tabel Outbox (Untuk mencatat event/kejadian secara asinkron)
        Schema::create('outbox_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // contoh: order.created, order.paid
            $table->json('payload');
            $table->boolean('published')->default(false);
            $table->timestamps();
        });

        // 2. Tabel Status Log (Audit Trail)
        Schema::create('order_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('from_status');
            $table->string('to_status');
            $table->foreignId('user_id')->nullable(); // Siapa yang ubah
            $table->timestamps();
        });

        // 3. Tabel Jurnal Akuntansi (SIA)
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('account_name'); // Piutang, Penjualan, HPP, dll
            $table->integer('debit')->default(0);
            $table->integer('credit')->default(0);
            $table->timestamps();
        });

        // 4. Tambah Indeks untuk Optimasi (Bab 6.4)
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']); // Mempercepat query dashboard admin
            $table->index(['customer_id', 'created_at']); // Mempercepat query riwayat pesanan
        });
    }

    public function down(): void {
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('order_status_logs');
        Schema::dropIfExists('outbox_events');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['customer_id', 'created_at']);
        });
    }
};