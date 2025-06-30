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
        // Ubah kolom user_id agar bisa menerima nilai NULL
        Schema::table('materials', function (Blueprint $table) {
            // ->nullable() mengizinkan nilai NULL. ->change() menerapkan perubahan.
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika untuk rollback: kembalikan kolom menjadi NOT NULL
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};