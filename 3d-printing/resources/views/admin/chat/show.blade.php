@extends('dashboard')

@section('title', 'Percakapan dengan ' . $selectedConversation->user->name)

@section('content')
{{-- class="h-full" akan membuat div ini mengisi penuh area <main> --}}
<div class="flex h-full w-full bg-white overflow-hidden">

    {{-- Panel Kiri: Daftar Percakapan --}}
    <div class="w-full md:w-[350px] lg:w-[380px] flex-shrink-0">
        @include('admin.chat._conversation-list', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation
        ])
    </div>

    {{-- Panel Kanan: Ruang Chat --}}
    <div id="chat-panel-wrapper" class="flex flex-grow flex-col">
        @include('admin.chat._chat-panel', [
            'selectedConversation' => $selectedConversation,
            'messages' => $messages
        ])
    </div>
    
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatPanelWrapper = document.getElementById('chat-panel-wrapper');
    const conversationListContainer = document.querySelector('.conversation-link')?.parentElement;
    const currentUserId = {{ Auth::id() }};

    /**
     * Fungsi inti untuk mengaktifkan semua fungsionalitas ruang chat.
     * Sekarang dengan logika yang lebih sederhana dan stabil.
     */
    function initializeChatRoom(conversationId) {
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const messagesContainer = document.getElementById('chatMessagesContainer');
        const attachFileBtn = document.getElementById('attach-file-btn');
        const imageInput = document.getElementById('image-input');
        const imagePreviewWrapper = document.getElementById('image-preview-wrapper');
        const imagePreview = document.getElementById('image-preview');
        const removeImageBtn = document.getElementById('remove-image-btn');
        let selectedFile = null;

        if (!chatForm || !messagesContainer || !attachFileBtn) {
            console.error('Satu atau lebih elemen chat tidak ditemukan. Inisialisasi dibatalkan.');
            return;
        }
        
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        function appendMessage(message) {
            // ... (Fungsi appendMessage tetap sama, tidak perlu diubah)
            const isSender = message.sender_id === currentUserId;
            const wrapperClass = isSender ? 'justify-end' : 'justify-start';
            const bubbleClass = isSender ? 'bg-blue-600 text-white' : 'bg-white text-gray-800';
            const imageHtml = message.image_path ? `<a href="/storage/${message.image_path}" target="_blank"><img src="/storage/${message.image_path}" class="mt-2 rounded-lg max-w-xs cursor-pointer"></a>` : '';
            const bodyHtml = message.body ? `<p class="text-sm">${message.body}</p>` : '';
            const messageHtml = `
                <div class="flex mb-4 ${wrapperClass}">
                    <div class="max-w-xl px-4 py-2 rounded-lg shadow ${bubbleClass}">
                        ${bodyHtml}
                        ${imageHtml}
                        <p class="text-xs text-right mt-1 opacity-60">${new Date(message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</p>
                    </div>
                </div>`;
            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // --- INI BAGIAN PERBAIKAN UTAMA ---
        // 1. Event listener untuk tombol paperclip
        attachFileBtn.addEventListener('click', () => {
            imageInput.click(); // Langsung trigger klik pada input file yang tersembunyi
        });

        // 2. Event listener untuk input file
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

        // 3. Event listener untuk menghapus preview
        removeImageBtn.addEventListener('click', () => {
            selectedFile = null;
            imageInput.value = '';
            imagePreviewWrapper.classList.add('hidden');
        });

        // 4. Event listener untuk form submit
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageText = messageInput.value.trim();
            if (messageText === '' && !selectedFile) return;

            const formData = new FormData();
            formData.append('body', messageText);
            if (selectedFile) {
                formData.append('image', selectedFile);
            }

            fetch(`/admin/chats/${conversationId}/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(sentMessage => {
                if (sentMessage.body || sentMessage.image_path) {
                    appendMessage(sentMessage);
                }
                messageInput.value = '';
                removeImageBtn.click();
            })
            .catch(error => console.error('Error:', error));
        });

        // --- Event Listener untuk WebSocket (tidak berubah) ---
        if (window.Echo) {
            // Hentikan listener lama sebelum memulai yang baru agar tidak duplikat
            Echo.leave(`chat.${conversationId}`);
            
            Echo.private(`chat.${conversationId}`).listen('.message.sent', e => {
                if (e.message.sender_id !== currentUserId) appendMessage(e.message);
            });
        }
    }

    // --- LISTENER UTAMA UNTUK MEMUAT KONTEN CHAT SECARA DINAMIS (tidak berubah) ---
    if (conversationListContainer) {
        conversationListContainer.addEventListener('click', function(event) {
            const link = event.target.closest('.conversation-link');
            if (link) {
                event.preventDefault();
                // ... (sisa kode untuk fetch dan update UI tetap sama)
                const url = link.dataset.url;
                // ...
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    chatPanelWrapper.innerHTML = html;
                    window.history.pushState({path: url}, '', url);
                    const conversationId = url.split('/').pop();
                    initializeChatRoom(conversationId);
                });
            }
        });
    }

    // Inisialisasi untuk halaman 'show' yang dimuat langsung
    const pathParts = window.location.pathname.split('/');
    if(pathParts[2] === 'chats' && pathParts.length > 3) {
        const conversationId = pathParts[3];
        initializeChatRoom(conversationId);
    }
});
</script>
@endpush