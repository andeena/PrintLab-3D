{{-- resources/views/components/dashboard/header.blade.php --}}
<header class="header-dashboard">
    <button id="hamburger-button" class="hamburger-button" aria-label="Toggle Menu" aria-expanded="false" aria-controls="sidebar">
        <svg class="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
        <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
    <h1 class="header-title">@yield('title', 'Dashboard')</h1>
    <div class="user-profile">
        {{-- Ganti dengan info user jika ada --}}
        <span>{{ Auth::user() ? Auth::user()->name : 'User' }}</span>
        {{-- Contoh Dropdown (perlu JS tambahan untuk fungsionalitas dropdown) --}}
        {{-- <img src="path/to/avatar.jpg" alt="User Avatar" class="avatar"> --}}
    </div>
    
</header>

