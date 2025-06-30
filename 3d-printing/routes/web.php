<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\DesignController as AdminDesignController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda bisa mendaftarkan web routes untuk aplikasi Anda.
|
*/

// Rute untuk halaman utama/landing page
Route::get('/', function () {
    return view('welcome');
});

//======================================================================
// RUTE UNTUK PENGGUNA BIASA (MEMERLUKAN LOGIN)
//======================================================================
Route::middleware('auth')->group(function () {
    
    // Rute Profil Pengguna (standar dari Breeze/Jetstream)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Dashboard Pengguna
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute untuk melihat Desain (milik sendiri atau publik)
    Route::get('/designs', [DesignController::class, 'index'])->name('designs.index');
    Route::get('/designs/{design}', [DesignController::class, 'show'])->name('designs.show');
    Route::get('/designs/{design}/download', [DesignController::class, 'download'])->name('designs.download')->middleware('auth');

    // Rute untuk melihat Material (milik sendiri atau publik)
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    
    // Rute Resource untuk Pesanan (sudah mencakup index, create, store, show, edit, update, destroy)
    Route::resource('orders', OrderController::class);

    //------------------------------------------------------------------
    // RUTE KONSULTASI / CHAT PENGGUNA (BAGIAN YANG DIPERBAIKI)
    //------------------------------------------------------------------
    // Menggunakan prefix URL /konsultasi agar lebih deskriptif.
    Route::prefix('konsultasi')->name('chat.')->group(function () {
        // URL: /konsultasi , Nama: chat.show
        // Rute ini tidak lagi memiliki parameter, jadi route('chat.show') akan berfungsi.
        Route::get('/', [ChatController::class, 'show'])->name('show');
        
        // URL: /konsultasi/send , Nama: chat.send
        Route::post('/send', [ChatController::class, 'send'])->name('send');
    });
});

//======================================================================
// RUTE UNTUK ADMIN (MEMERLUKAN LOGIN & ROLE ADMIN)
//======================================================================
Route::middleware(['auth', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Rute Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Rute Manajemen Pesanan oleh Admin
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verifyPayment');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

    // Rute Resource untuk Material & Desain oleh Admin (full CRUD)
    Route::resource('materials', AdminMaterialController::class);
    Route::resource('designs', AdminDesignController::class);

    // Rute Manajemen Chat oleh Admin (struktur ini sudah baik)
    Route::prefix('chats')->name('chat.')->group(function () {
        
        // Menampilkan daftar semua percakapan. URL: /admin/chats, Nama: admin.chat.index
        Route::get('/', [AdminChatController::class, 'index'])->name('index');
        
        // Menampilkan satu percakapan spesifik. URL: /admin/chats/{conversation}, Nama: admin.chat.show
        Route::get('/{conversation}', [AdminChatController::class, 'show'])->name('show');
        
        // Menyimpan pesan balasan dari admin. URL: POST /admin/chats/{conversation}/messages, Nama: admin.chat.storeMessage
        Route::post('/{conversation}/messages', [AdminChatController::class, 'storeMessage'])->name('storeMessage');
    });
});

// Include file route otentikasi (login, register, dll.)
require __DIR__.'/auth.php';