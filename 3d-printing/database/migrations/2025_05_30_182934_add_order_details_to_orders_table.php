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
            // Tambahkan kolom setelah kolom 'user_id' atau 'status' yang sudah ada
            // Sesuaikan 'after()' jika urutan kolom Anda berbeda

            // Kolom foreign key ke tabel designs (jika pesanan bisa terkait dengan desain)
            // onDelete('set null') berarti jika desain dihapus, design_id di order akan jadi NULL
            $table->foreignId('design_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            
            $table->string('material')->nullable()->after('design_id');
            $table->string('color')->nullable()->after('material');
            $table->integer('quantity')->default(1)->after('color');
            $table->text('notes')->nullable()->after('quantity');
            
            // Detail Pengiriman (dibuat tidak nullable karena di form sebelumnya 'required')
            $table->string('recipient_name')->after('notes');
            $table->text('shipping_address')->after('recipient_name');
            $table->string('phone_number')->after('shipping_address');
            
            // Anda mungkin ingin mengubah default atau nullability kolom status & total_price yang sudah ada
            // Contoh: jika status dan total_price harus diisi controller, bukan default DB
            // $table->string('status')->default('pending')->change(); // Jika ingin memastikan ada default
            // $table->decimal('total_price', 15, 2)->nullable()->change(); // Jika total_price dihitung nanti
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom dalam urutan terbalik atau dengan aman
            // Penting untuk menghapus foreign key constraint dulu sebelum kolomnya
            $table->dropForeign(['design_id']); // Nama constraint biasanya 'orders_design_id_foreign'

            $table->dropColumn([
                'design_id',
                'material',
                'color',
                'quantity',
                'notes',
                'recipient_name',
                'shipping_address',
                'phone_number',
            ]);
        });
    }
};