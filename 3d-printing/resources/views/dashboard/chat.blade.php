@extends('dashboard')

@section('title', 'Konsultasi dengan Admin')

@push('styles')
<style>
    /* Menyesuaikan layout content-wrapper agar chat terpusat */
    .content-wrapper { display: flex; justify-content: center; align-items: center; padding: 1rem; width: 100%; }

    /* Kontainer utama chat */
    .chat-container {
        width: 100%;
        max-width: 900px; /* Lebar yang lebih umum untuk chat */
        height: calc(100vh - 120px); /* Mengisi tinggi dikurangi header dan padding */
        min-height: 600px; /* Batas tinggi minimal */
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    /* Header chat dengan warna tema */
    .chat-header { padding: 1rem 1.5rem; background: linear-gradient(to right, #FFC107, #FF9800); color: white; font-size: 1.1rem; font-weight: 600; flex-shrink: 0; }
    
    /* Area pesan */
    .chat-messages {
        flex-grow: 1;
        padding: 1.5rem;
        overflow-y: auto;
        background-color: #FFFBEB; /* Latar kuning sangat muda */
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    /* Scrollbar Kustom */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

    /* Bubble pesan */
    .message { padding: 0.75rem 1rem; border-radius: 18px; max-width: 75%; line-height: 1.5; display: flex; flex-direction: column; }
    .message-body { font-size: 0.95em; word-wrap: break-word; }
    .message-time { font-size: 0.7rem; align-self: flex-end; margin-top: 4px; opacity: 0.8; }
    .message.sent { background: #FFA726; color: white; align-self: flex-end; border-bottom-right-radius: 6px; }
    .message.received { background-color: #FFFFFF; color: #333; align-self: flex-start; border: 1px solid #eee; border-bottom-left-radius: 6px; }
    
    /* Style untuk gambar di dalam bubble chat */
    .message-image {
        margin-top: 8px;
        border-radius: 12px;
        max-width: 280px;
        max-height: 350px;
        width: auto;
        height: auto;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .message.received .message-image {
        border-color: #f0f0f0;
    }
    
    /* Area input */
    .chat-input-area { display: flex; padding: 0.75rem 1rem; background-color: #fff; border-top: 1px solid #f0f0f0; flex-shrink: 0; gap: 0.75rem; align-items: center; }
    #chatForm { width: 100%; display: flex; gap: 0.75rem; align-items: center; }
    .chat-input-area input[type="text"] { flex-grow: 1; padding: 0.65rem 1rem; border: 1px solid #ddd; border-radius: 9999px; outline: none; transition: all 0.2s ease; }
    .chat-input-area input[type="text"]:focus { border-color: #FFA000; box-shadow: 0 0 0 3px rgba(255, 160, 0, 0.2); }
    .chat-input-area button { padding: 0.65rem; background: #FF9800; color: white; border: none; border-radius: 9999px; cursor: pointer; font-weight: 600; display:flex; align-items:center; justify-content:center; transition: background-color 0.2s ease; flex-shrink: 0; }
    .chat-input-area button:hover { background-color: #F57C00; }
    .chat-input-area button#sendButton { padding-left: 1.25rem; padding-right: 1.25rem; }
    
    /* Preview Gambar */
    #image-preview-wrapper { margin-bottom: 0.5rem; padding: 0.5rem; background-color: #f0f0f0; border-radius: 8px; position: relative; width: fit-content; }
    #image-preview { height: 60px; width: 60px; border-radius: 6px; object-fit: cover; }
    #remove-image-btn { position: absolute; top: -8px; right: -8px; background-color: #ef4444; color: white; border-radius: 9999px; height: 24px; width: 24px; display:flex; align-items:center; justify-content:center; border: 2px solid white; cursor: pointer; }

</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="chat-container">
        <div class="chat-header">Konsultasi dengan Admin</div>
        <div class="chat-messages custom-scrollbar" id="chatMessagesContainer">
            {{-- Pesan yang sudah ada akan dimuat di sini dari controller --}}
            @forelse ($messages as $message)
                <div class="message {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
                    @if ($message->body)
                        <p class="message-body">{{ $message->body }}</p>
                    @endif
                    
                    {{-- FIX: Bagian ini sudah aktif dan akan menampilkan gambar --}}
                    {{-- Pastikan nama kolom di database Anda adalah 'image_path' --}}
                    @if ($message->image_path)
                        <a href="{{ asset('storage/' . $message->image_path) }}" target="_blank">
                            <img src="{{ asset('storage/' . $message->image_path) }}" alt="Gambar Chat" class="message-image">
                        </a>
                    @endif

                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                </div>
            @empty
                <p class="text-center text-gray-500 my-auto">Mulai percakapan dengan mengirim pesan pertama Anda.</p>
            @endforelse
        </div>
        
        <div class="chat-input-area">
            {{-- Form untuk mengirim pesan dan/atau gambar --}}
            <form id="chatForm" autocomplete="off">
                {{-- Tombol Lampirkan File --}}
                <button id="attach-file-btn" type="button" title="Lampirkan Gambar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                </button>
                <input type="file" id="image-input" class="hidden" accept="image/*">

                <div class="w-full">
                    {{-- Preview Gambar yang akan dikirim --}}
                    <div id="image-preview-wrapper" class="hidden">
                        <img id="image-preview">
                        <button id="remove-image-btn" type="button" title="Hapus Gambar">&times;</button>
                    </div>
                    {{-- Input Teks --}}
                    <input type="text" id="messageInput" placeholder="Ketik pesan Anda...">
                </div>

                {{-- Tombol Kirim --}}
                <button type="submit" id="sendButton">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Referensi Elemen & Variabel ---
    const conversationId = {{ $conversation->id ?? 'null' }}; // Diberi fallback jika conversation null
    const currentUserId = {{ Auth::id() ?? 0 }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const messagesContainer = document.getElementById('chatMessagesContainer');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    // Untuk upload gambar
    const attachFileBtn = document.getElementById('attach-file-btn');
    const imageInput = document.getElementById('image-input');
    const imagePreviewWrapper = document.getElementById('image-preview-wrapper');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image-btn');
    let selectedFile = null;

    // --- Fungsi Bantuan ---
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }
    
    // Scroll ke pesan terakhir saat halaman dimuat
    scrollToBottom();

    function appendMessage(message) {
        const emptyMessage = messagesContainer.querySelector('p.text-center');
        if (emptyMessage) emptyMessage.remove();

        const messageDiv = document.createElement('div');
        const messageClass = message.sender_id === currentUserId ? 'sent' : 'received';
        messageDiv.classList.add('message', messageClass);

        let innerHTML = '';
        if (message.body) {
            // Sanitasi sederhana untuk mencegah XSS
            const bodyP = document.createElement('p');
            bodyP.classList.add('message-body');
            bodyP.textContent = message.body;
            innerHTML += bodyP.outerHTML;
        }
        
        // FIX: Menampilkan gambar baru yang diterima
        if (message.image_path) {
            const imageUrl = `/storage/${message.image_path}`;
            innerHTML += `<a href="${imageUrl}" target="_blank"><img src="${imageUrl}" alt="Gambar Chat" class="message-image"></a>`;
        }
        
        const time = new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
        innerHTML += `<span class="message-time">${time}</span>`;
        
        messageDiv.innerHTML = innerHTML;
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // --- Event Listeners untuk Upload Gambar ---
    attachFileBtn.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', () => {
        if (imageInput.files && imageInput.files[0]) {
            selectedFile = imageInput.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                imagePreview.src = e.target.result;
                imagePreviewWrapper.classList.remove('hidden');
            };
            reader.readAsDataURL(selectedFile);
        }
    });

    removeImageBtn.addEventListener('click', () => {
        selectedFile = null;
        imageInput.value = '';
        imagePreviewWrapper.classList.add('hidden');
    });

    // --- Event Listener Utama untuk Mengirim Pesan ---
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const messageText = messageInput.value.trim();

        if (messageText === '' && !selectedFile) return;

        const formData = new FormData();
        formData.append('message', messageText); // Nama field ini harus cocok dengan validasi di Controller
        
        if (selectedFile) {
            formData.append('image', selectedFile); // Jika Controller Anda handle 'image'
        }

        messageInput.disabled = true;
        sendButton.disabled = true;

        fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err });
            }
            return response.json();
        })
        .then(sentMessage => {
            appendMessage(sentMessage);
            messageInput.value = '';
            removeImageBtn.click();
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessage = error.errors?.message?.[0] || 'Terjadi kesalahan saat mengirim pesan.';
            alert('Gagal mengirim: ' + errorMessage);
        })
        .finally(() => {
            messageInput.disabled = false;
            sendButton.disabled = false;
            messageInput.focus();
        });
    });

    // --- Listener untuk Real-time Event (jika menggunakan Laravel Echo) ---
    if (window.Echo && conversationId) {
        Echo.private(`chat.${conversationId}`)
            .listen('.message.sent', (e) => {
                if (e.message.sender_id !== currentUserId) {
                    appendMessage(e.message);
                }
            });
    }
});
</script>
@endpush