<aside class="sidebar" id="sidebar">
    <div class="app-logo">
        PrintLab 3D
    </div>
    
    <nav class="flex flex-col h-full"> {{-- Tambahkan class ini agar bisa mendorong logout ke bawah --}}
        {{-- Link Navigasi Utama --}}
        <div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('designs.index') }}" class="{{ request()->routeIs('designs.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {{-- Anda bisa cari ikon SVG yang lebih cocok untuk "Desain" --}}
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c.251.023.501.05.75.082a9.75 9.75 0 018.25 8.25c.032.249.059.499.082.75m0 0H3.75m16.5 0c.023-.25.05-.5.082-.75a9.75 9.75 0 00-8.25-8.25c-.249-.032-.5-.059-.75-.082m-7.5 0v5.714c0 .823.312 1.591.878 2.158l5.25 5.25a2.25 2.25 0 003.182 0l5.25-5.25a2.25 2.25 0 00.878-2.158v-5.714m-16.5 0c1.02.053 2.046.223 3 .519m10.5 0c.954-.296 1.98-.466 3-.519" />
                </svg>
                <span>Galeri Desain</span>
            </a>
            
            <a href="{{ route('materials.index') }}" class="{{ request()->routeIs('materials.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                <span>Katalog Material</span>
            </a>

            <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Pesanan</span>
            </a>
            
            <a href="{{ route('chat.show') }}" class="{{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Konsultasi</span>
            </a>
        </div>

        {{-- LINK LOGOUT (Diletakkan di bagian bawah) --}}
        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="hover:bg-red-100 hover:text-red-700"> {{-- Beri warna berbeda untuk logout --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </a>
            </form>
            <div class="sidebar-footer">
                © {{ date('Y') }} PrintLab 3D
            </div>
        </div>
    </nav>
</aside>