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
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            // Kolom foreign key ke tabel users
            
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            // Kolom status pesanan
            
            $table->decimal('total_price', 10, 2)->default(0);
            // Kolom total harga
            
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
