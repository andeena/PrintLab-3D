<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom foreign key untuk relasi ke tabel 'designs'
            // onDelete('set null') berarti jika desain dihapus, kolom ini akan jadi NULL
            $table->foreignId('design_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key dulu sebelum menghapus kolomnya
            $table->dropForeign(['design_id']);
            $table->dropColumn('design_id');
        });
    }
};