<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message; // Pastikan Anda juga punya model Message
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception; // Diperlukan untuk error handling

class ChatController extends Controller
{
    /**
     * Menampilkan halaman chat untuk pengguna.
     * Jika percakapan belum ada, akan dibuat otomatis.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Cari atau buat percakapan untuk user ini
        $conversation = Conversation::firstOrCreate(
            ['user_id' => $user->id]
            // Tidak perlu set last_message_at di sini, akan diupdate saat pesan dikirim
        );
        
        // Ambil pesan-pesan dari percakapan tersebut. Eager load 'sender' untuk efisiensi.
        $messages = $conversation->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        // Mengirim data yang dibutuhkan ke view
        return view('dashboard.chat', [ // Sesuaikan path view jika perlu
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Menyimpan dan menyiarkan pesan (teks dan/atau gambar) yang dikirim oleh pengguna.
     * VERSI YANG DIPERBAIKI UNTUK MENANGANI GAMBAR
     */
    public function send(Request $request)
    {
        // 1. Validasi Input
        // - 'message' tidak wajib jika ada 'image'
        // - 'image' tidak wajib jika ada 'message'
        // - Setidaknya salah satu harus ada
        $validated = $request->validate([
            'message' => 'required_without:image|nullable|string|max:1000',
            'image'   => 'required_without:message|nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // maks 2MB
        ]);

        try {
            $user = Auth::user();
            $conversation = Conversation::firstOrCreate(['user_id' => $user->id]);
            $imagePath = null;

            // 2. Proses Upload File Gambar jika ada
            if ($request->hasFile('image')) {
                // Simpan file ke storage/app/public/chat_images dan dapatkan path-nya
                $imagePath = $request->file('image')->store('chat_images', 'public');
            }

            // 3. Siapkan data untuk disimpan ke database
            $messageData = [
                'sender_id' => $user->id,
                'body' => $validated['message'] ?? null,
                'image_path' => $imagePath, // Akan bernilai null jika tidak ada gambar
            ];

            // Cegah pengiriman pesan yang benar-benar kosong
            if (is_null($messageData['body']) && is_null($messageData['image_path'])) {
                return response()->json(['error' => 'Pesan tidak boleh kosong.'], 422);
            }

            // 4. Simpan pesan baru ke database
            $message = $conversation->messages()->create($messageData);

            // 5. Update timestamp pesan terakhir di percakapan
            $conversation->touch('last_message_at');

            // 6. Siarkan event agar admin bisa menerima secara real-time (jika Anda menggunakan WebSockets)
            // Pastikan Anda sudah setup Laravel Echo/Pusher untuk ini
            // broadcast(new MessageSent($message))->toOthers();

            // 7. Kembalikan response sukses dalam format JSON, sertakan data pengirim
            return response()->json($message->load('sender'));

        } catch (Exception $e) {
            // Jika terjadi error saat menyimpan, catat error dan kirim response 500
            report($e);
            return response()->json(['error' => 'Gagal mengirim pesan karena kesalahan server.'], 500);
        }
    }
}