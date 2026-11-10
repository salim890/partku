<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Kode OTP (6 angka)
            $table->string('otp_code')->nullable();
            // Waktu kedaluwarsa (misal 10 menit dari sekarang)
            $table->timestamp('otp_expires_at')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_expires_at']);
        });
    }
};