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
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            
            // Kolom dan Foreign Key yang sudah diperbaiki
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'fk_designs_users_user_id') // Memberi nama unik pada constraint
                  ->references('id')->on('users')->onDelete('cascade');

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};