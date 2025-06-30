{{-- File: resources/views/admin/chat/_chat-panel.blade.php --}}

{{-- Header Ruang Chat --}}
<div class="flex items-center p-3.5 border-b border-gray-200 flex-shrink-0 bg-white">
    <img class="h-10 w-10 rounded-full object-cover mr-3" src="https://ui-avatars.com/api/?name={{ urlencode($selectedConversation->user->name ?? 'U') }}&background=EBF4FF&color=0D6EFD" alt="Avatar">
    <div>
        <p class="font-semibold text-slate-700">{{ $selectedConversation->user->name ?? 'Pengguna Dihapus' }}</p>
        <p class="text-xs text-green-500">Online</p>
    </div>
</div>

{{-- Area Pesan --}}
<div id="chatMessagesContainer" class="flex-1 p-4 overflow-y-auto bg-gray-50">
    @forelse ($messages as $message)
        <div class="flex mb-4 {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
            {{-- PERBAIKAN: Gunakan tema biru dan oranye yang sudah kita tentukan --}}
            <div class="max-w-xl px-4 py-2 rounded-lg shadow {{ $message->sender_id == Auth::id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-800' }}">
                
                {{-- Tampilkan teks jika ada --}}
                @if ($message->body)
                    <p class="text-sm">{{ $message->body }}</p>
                @endif
                
                {{-- PERBAIKAN: Tampilkan gambar jika ada --}}
                @if ($message->image_path)
                    {{-- Diberi link agar bisa dibuka di tab baru, dan class agar ukurannya pas --}}
                    <a href="{{ asset('storage/' . $message->image_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $message->image_path) }}" class="mt-2 rounded-lg max-w-xs cursor-pointer hover:opacity-90 transition">
                    </a>
                @endif

                <p class="text-xs text-right mt-1 opacity-60">{{ $message->created_at->format('H:i') }}</p>
            </div>
        </div>
    @empty
        <p class="text-center text-gray-500 p-4">Belum ada pesan dalam percakapan ini.</p>
    @endforelse
</div>

{{-- Area Input Pesan --}}
<div class="p-3 border-t border-gray-200 flex-shrink-0 bg-gray-100">

    {{-- PERBAIKAN: Area untuk Preview Gambar --}}
    <div id="image-preview-wrapper" class="hidden mb-2 p-2 bg-gray-200 rounded-lg relative w-24">
        <img id="image-preview" class="h-20 w-20 rounded-md object-cover">
        <button id="remove-image-btn" type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold">&times;</button>
    </div>

    <form id="chatForm" class="flex items-center space-x-2">
        {{-- PERBAIKAN: Tombol Lampirkan File --}}
        <button id="attach-file-btn" type="button" title="Lampirkan Gambar" class="p-2 text-gray-500 hover:text-orange-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
        </button>
        {{-- Input file yang asli kita sembunyikan --}}
        <input type="file" id="image-input" class="hidden" accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">

        <input type="text" id="messageInput" placeholder="Ketik balasan Anda..." class="flex-1 border border-gray-300 rounded-full py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" autocomplete="off">
        <button type="submit" title="Kirim Pesan" class="flex items-center justify-center bg-orange-500 hover:bg-orange-600 rounded-full text-white w-10 h-10 flex-shrink-0 shadow-sm transition">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 16.571V11.5a1 1 0 011-1h2a1 1 0 011 1v5.071a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
        </button>
    </form>
</div>