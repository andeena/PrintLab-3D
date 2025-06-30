<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom untuk menyimpan metode pembayaran yang dipilih (e.g., 'mandiri', 'gopay')
            $table->string('payment_method')->nullable()->after('total_price');

            // Kolom untuk menyimpan path/nama file dari bukti pembayaran
            $table->string('payment_proof')->nullable()->after('payment_method');

            // Kolom untuk status verifikasi pembayaran oleh admin
            $table->string('payment_status')->default('unpaid')->after('payment_proof');
            // Pilihan status: unpaid, pending_verification, paid, rejected
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_proof', 'payment_status']);
        });
    }
};