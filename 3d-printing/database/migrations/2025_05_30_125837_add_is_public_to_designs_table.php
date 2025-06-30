<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            // Tambahkan kolom is_public sebagai boolean, defaultnya false (tidak publik)
            // Anda bisa meletakkannya setelah kolom lain, misalnya 'price' atau 'file_size'
            $table->boolean('is_public')->default(false)->after('price'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
};