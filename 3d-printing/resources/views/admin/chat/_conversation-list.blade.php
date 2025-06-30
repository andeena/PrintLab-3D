{{-- File: resources/views/admin/chat/_conversation-list.blade.php --}}

<div class="bg-white h-full flex flex-col">
    {{-- Header Panel --}}
    <div class="p-4 border-b border-gray-200 flex-shrink-0">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Percakapan</h2>
        <p class="text-sm text-gray-500">Pilih pengguna untuk dilihat.</p>
    </div>

    {{-- Daftar Percakapan Aktual --}}
    <div class="flex-grow overflow-y-auto">
        @forelse ($conversations as $convo)
            {{--
                ============================================================
                PERBAIKAN KUNCI UNTUK AJAX:
                1. class="conversation-link" : Penanda untuk JavaScript.
                2. data-url="..."          : Menyimpan URL yang akan diambil oleh JavaScript.
                href="..."                 : Tetap ada sebagai fallback jika JS gagal.
                ============================================================
            --}}
            <a href="{{ route('admin.chat.show', ['conversation' => $convo->id]) }}"
               data-url="{{ route('admin.chat.show', ['conversation' => $convo->id]) }}"
               class="conversation-link flex items-center p-4 border-l-4 hover:bg-gray-50 transition duration-150 ease-in-out cursor-pointer 
                      {{ isset($selectedConversation) && $selectedConversation->id == $convo->id ? 'bg-blue-50 !border-blue-600' : 'border-transparent' }}">

                <div class="relative flex-shrink-0 mr-4">
                    <img class="h-11 w-11 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($convo->user->name ?? 'U') }}&background=EBF4FF&color=0D6EFD" alt="Avatar">
                </div>

                <div class="flex-grow min-w-0">
                    <div class="flex justify-between items-center">
                        <p class="text-md font-semibold text-gray-800 truncate">{{ $convo->user->name ?? 'Pengguna Dihapus' }}</p>
                        @if ($convo->last_message_at)
                           <p class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ $convo->last_message_at->diffForHumans() }}</p>
                        @endif
                    </div>
                    
                    {{-- MENAMBAHKAN PREVIEW PESAN TERAKHIR & UNREAD COUNT --}}
                    <div class="flex justify-between items-start mt-1">
                        <p class="text-sm text-gray-500 truncate pr-4">
                            {{-- Tampilkan preview pesan terakhir. Anda perlu memuat relasi 'latestMessage' di controller --}}
                            {{ optional($convo->latestMessage)->body ?? 'Tidak ada pesan...' }}
                        </p>
                        
                        {{-- Tampilkan badge jika ada pesan belum dibaca. Anda perlu menghitung 'unread_count' di controller --}}
                        @if(isset($convo->unread_count) && $convo->unread_count > 0)
                            <span class="flex items-center justify-center h-5 w-5 text-xs font-bold text-white bg-blue-600 rounded-full flex-shrink-0">
                                {{ $convo->unread_count }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center p-10">
                <p class="text-sm text-gray-500">Tidak ada percakapan ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>