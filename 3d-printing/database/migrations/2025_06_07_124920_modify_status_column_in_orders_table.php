<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa import DB Facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // LANGKAH 1: Ubah dulu tipe kolom menjadi string agar fleksibel
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->change();
        });

        // LANGKAH 2: Setelah kolomnya fleksibel, baru update data lama ke ejaan yang baru.
        DB::table('orders')->where('status', 'canceled')->update(['status' => 'cancelled']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika untuk rollback jika diperlukan
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan ke enum jika rollback (sesuaikan dengan enum awal Anda)
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending')->change();
        });

        // Update kembali datanya jika di-rollback
        DB::table('orders')->where('status', 'cancelled')->update(['status' => 'canceled']);
    }
};