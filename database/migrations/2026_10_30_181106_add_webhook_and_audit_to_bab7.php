<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Tabel Webhook Logs (Untuk Idempotency - Bab 7.2.1)
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique(); // Kunci Idempotency
            $table->string('event_type'); // payment.paid, logistics.shipped, dll
            $table->json('payload');
            $table->timestamps();
        });

        // 2. Tambah Kolom di Audit Trail (Bab 7.5.1)
        Schema::table('order_status_logs', function (Blueprint $table) {
            $table->string('actor_type')->default('admin'); // 'admin' atau 'webhook'
            $table->string('reason_code')->nullable(); // Alasan perubahan
        });
    }

    public function down(): void {
        Schema::dropIfExists('webhook_logs');
        Schema::table('order_status_logs', function (Blueprint $table) {
            $table->dropColumn(['actor_type', 'reason_code']);
        });
    }
};