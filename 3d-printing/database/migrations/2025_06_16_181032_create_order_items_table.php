<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // dalam file ...create_order_items_table.php
public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('material_id')->constrained()->onDelete('restrict');
        $table->string('design_name'); // Atau foreignId ke 'designs' jika perlu
        $table->string('color');
        $table->integer('quantity');
        $table->decimal('price', 15, 2); // Harga per item saat dipesan
        $table->decimal('subtotal', 15, 2); // Subtotal (harga * jumlah)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
