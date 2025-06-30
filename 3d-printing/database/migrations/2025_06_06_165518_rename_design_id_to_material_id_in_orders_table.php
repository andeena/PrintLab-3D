<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key lama dulu
            $table->dropForeign(['design_id']);
            // Ubah nama kolom
            $table->renameColumn('design_id', 'material_id');
            // Buat kembali foreign key dengan nama kolom yang baru
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->renameColumn('material_id', 'design_id');
            $table->foreign('design_id')->references('id')->on('designs')->onDelete('set null');
        });
    }
};