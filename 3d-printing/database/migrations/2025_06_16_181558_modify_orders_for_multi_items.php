<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._modify_orders_for_multi_items.php
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        // HAPUS KOLOM-KOLOM INI KARENA SUDAH PINDAH KE order_items
        $table->dropForeign(['design_id']); // Hapus foreign key dulu jika ada
        $table->dropForeign(['material_id']); // Hapus foreign key dulu jika ada
        $table->dropColumn([
            'design_id',
            'material_id',
            'material',
            'color',
            'quantity'
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
