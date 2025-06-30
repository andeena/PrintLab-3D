<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar semua percakapan.
     */
    public function index()
    {
        // PERBAIKAN: Tambahkan with('latestMessage') untuk mengambil preview pesan terakhir.
        // Ini dibutuhkan oleh _conversation-list.blade.php
        $conversations = Conversation::with(['user', 'latestMessage'])
                            ->orderBy('last_message_at', 'desc')
                            ->get();

        return view('admin.chat.index', compact('conversations'));
    }

    /**
     * Menampilkan satu percakapan spesifik beserta isinya.
     */
    public function show(Request $request, Conversation $conversation)
    {
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        // Jika ini adalah request AJAX, kirim hanya panel chat.
        if ($request->ajax()) {
            return view('admin.chat._chat-panel', [
                'selectedConversation' => $conversation,
                'messages' => $messages
            ]);
        }
        
        // PERBAIKAN: Tambahkan with('latestMessage') juga di sini untuk sidebar.
        $conversations = Conversation::with(['user', 'latestMessage'])
                            ->orderBy('last_message_at', 'desc')
                            ->get();
        
        return view('admin.chat.show', [
            'conversations' => $conversations,
            'selectedConversation' => $conversation,
            'messages' => $messages
        ]);
    }

    /**
     * Menyimpan pesan baru (teks dan/atau gambar) yang dikirim admin.
     */
    public function storeMessage(Request $request, Conversation $conversation)
    {
        // PERBAIKAN: Validasi diubah untuk bisa menerima gambar.
        $request->validate([
            'body' => 'nullable|required_without:image|string|max:1000',
            'image' => 'nullable|required_without:body|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Maks 2MB
        ]);

        $imagePath = null;
        
        // PERBAIKAN: Tambahkan logika untuk menyimpan file jika ada.
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        // PERBAIKAN: Simpan path gambar ke database.
        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $request->body,
            'image_path' => $imagePath, // Kolom baru ditambahkan
        ]);
        
        $conversation->update(['last_message_at' => now()]);
        
        // PERBAIKAN: Pastikan me-load 'sender' sebelum broadcast dan return.
        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}