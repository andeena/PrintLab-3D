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
    Schema::table('order_items', function (Blueprint $table) {
        // Kolom ini bisa null karena pesanan bisa saja kustom tanpa memilih desain
        $table->foreignId('design_id')->nullable()->after('order_id')->constrained()->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropForeign(['design_id']);
        $table->dropColumn('design_id');
    });
}
};
