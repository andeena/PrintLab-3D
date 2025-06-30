<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan semua channel broadcasting event yang
| didukung oleh aplikasi Anda. Dengan otorisasi yang tepat, Laravel akan
| menyiarkan event ke channel privat atau presence channel.
|
*/

// Otorisasi channel default untuk notifikasi per user
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Otorisasi untuk channel chat privat kita
Broadcast::channel('chat.{conversationId}', function (User $user, $conversationId) {
    // Cari percakapan berdasarkan ID yang diberikan
    $conversation = Conversation::find($conversationId);

    // Jika percakapan tidak ditemukan, tolak akses.
    if (!$conversation) {
        return false;
    }

    // Izinkan user untuk mendengarkan channel ini HANYA JIKA
    // ID user yang sedang login sama dengan user_id atau admin_id di percakapan.
    return $user->id === $conversation->user_id || $user->id === $conversation->admin_id;
});