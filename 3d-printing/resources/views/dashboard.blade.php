<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PrintLab 3D') }} | @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Warna Dasar Tema (Dengan Aksen Oranye) */
            --color-grad-yellow: #f6d365;
            --color-grad-orange: #fda085;
            --color-primary-orange: #FFA500;
            --color-primary-orange-dark: #F97316;
            --color-accent-yellow-gold: #FFD700;
            --color-black: #111827;
            --color-white: #FFFFFF;
            --color-bg-sidebar-light: #FFFBEB; /* Kuning Sangat Muda untuk Sidebar, Header, Card & Footer */
            --color-text-dark: #1F2937;
            --color-text-sidebar-default: #5D4037; /* Coklat tua untuk teks utama di sidebar, header, & pagination */
            --color-text-sidebar-muted: #795548;  /* Coklat lebih muda untuk teks muted & pagination disabled */
            --color-text-muted: #6B7280; /* Abu-abu untuk teks muted umum */
            --color-border: #E5E7EB;
            --color-border-sidebar: #FDE68A; /* Border kuning sedikit lebih tua */

            /* Variabel Semantik (Dengan Aksen Oranye) */
            --primary: var(--color-primary-orange);
            --primary-dark: var(--color-primary-orange-dark);
            --primary-light-accent: var(--color-accent-yellow-gold);

            --text-default: var(--color-text-dark); /* Teks default untuk konten dalam card */
            --text-muted-on-light: var(--color-text-muted);

            --bg-body-gradient: linear-gradient(120deg, var(--color-grad-yellow) 0%, var(--color-grad-orange) 100%);
            --bg-sidebar: var(--color-bg-sidebar-light);
            --bg-header: var(--color-bg-sidebar-light);
            --bg-content-area: transparent;
            --bg-card: var(--color-bg-sidebar-light);
            --bg-footer: var(--color-bg-sidebar-light);

            --text-logo-sidebar: var(--primary-dark); /* Oranye Tua untuk logo */
            --text-on-sidebar: var(--color-text-sidebar-default); /* Teks coklat tua di sidebar/header */
            --text-link-sidebar-hover: var(--primary-dark); /* Link hover di sidebar jadi Oranye Tua */
            --bg-link-sidebar-hover: var(--color-white);
            --text-link-sidebar-active: var(--color-white);
            --bg-link-sidebar-active: var(--primary); /* Latar link aktif di sidebar jadi Oranye */
            --border-sidebar-internal: var(--color-border-sidebar);

            --btn-primary-bg: var(--primary); /* Tombol utama Oranye */
            --btn-primary-text: var(--color-white);
            --btn-primary-hover-bg: var(--primary-dark); /* Hover tombol jadi Oranye Tua */

            --sidebar-width-desktop: 240px;
            --sidebar-width-mobile: 280px;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-body-gradient);
            color: var(--text-default);
            display: flex;
            overflow: hidden; /* Mencegah scroll di body karena layout flex full height */
        }

        .dashboard-container {
            display: flex;
            flex-grow: 1;
            width: 100%;
            position: relative; /* Untuk positioning sidebar mobile */
        }

        .sidebar {
            background: var(--bg-sidebar);
            color: var(--text-on-sidebar);
            padding: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0,0,0,0.06);
            border-right: 1px solid var(--border-sidebar-internal);
            width: var(--sidebar-width-desktop);
            flex-shrink: 0;
            transition: width 0.3s ease-in-out, padding 0.3s ease-in-out, transform 0.3s ease-in-out;
            z-index: 1000; /* Di atas konten lain */
            height: 100vh; /* Tinggi penuh viewport */
            /* Untuk desktop, position diatur oleh flex parent, tidak sticky/fixed */
        }
        .app-logo { font-size: 1.5rem; font-weight: 700; color: var(--text-logo-sidebar); text-align: center; padding-bottom: 20px; margin-bottom: 20px; border-bottom: 1px solid var(--border-sidebar-internal); flex-shrink: 0; min-height: 2.5em; display: flex; align-items: center; justify-content: center; }
        .sidebar nav { flex-grow: 1; overflow-y: auto; }
        .sidebar a {
            display: flex;
            align-items: center; 
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            color: var(--text-on-sidebar);
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        .sidebar a:hover { background-color: var(--bg-link-sidebar-hover); color: var(--text-link-sidebar-hover); transform: translateX(3px); }
        .sidebar a.active { background-color: var(--bg-link-sidebar-active); color: var(--text-link-sidebar-active); font-weight: 600; }
        .sidebar a.active svg { color: var(--text-link-sidebar-active); }
        .sidebar a svg { margin-right: 12px; width: 20px; height: 20px; color: var(--text-on-sidebar); flex-shrink: 0; transition: margin 0.3s ease-in-out; }
        .sidebar a:hover svg { color: var(--text-link-sidebar-hover); }
        .sidebar-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border-sidebar-internal); text-align: center; font-size: 0.85rem; color: var(--color-text-sidebar-muted); flex-shrink: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-notification { margin-left: auto; background-color: var(--primary); color: var(--color-white); font-size: 0.7rem; padding: 2px 6px; border-radius: 10px; font-weight: bold; }


        .page-content-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            height: 100vh; /* Tinggi penuh viewport */
            overflow: hidden; /* Mencegah wrapper ini scroll, yang scroll adalah .main-scrollable-content */
            background-color: var(--bg-content-area);
             /* Transisi margin-left tidak diperlukan jika sidebar desktop width-nya diubah */
        }

        .header-dashboard {
            background: var(--bg-header);
            padding: 0 25px;
            height: 60px; /* Tinggi header tetap */
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-sidebar-internal);
            box-shadow: 0 1px 3px rgba(93, 64, 55, 0.08);
            flex-shrink: 0; /* Tidak menyusut */
            position: sticky; /* Menempel di atas .main-scrollable-content */
            top: 0;
            z-index: 900;
        }
        .header-dashboard h1.header-title { color: var(--text-on-sidebar); font-size: 1.25rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-profile { display: flex; align-items: center; color: var(--text-on-sidebar); }
        .user-profile span { color: var(--text-on-sidebar); white-space: nowrap; }
        .user-profile .avatar { width: 32px; height: 32px; border-radius: 50%; margin-left: 10px; object-fit: cover; border: 1px solid var(--border-sidebar-internal); }
        .hamburger-button { background: none; border: none; cursor: pointer; padding: 10px; margin-right: 15px; color: var(--text-on-sidebar); display: none; /* Default tersembunyi, diatur oleh JS/media query */ }
        .hamburger-button svg { width: 24px; height: 24px; display: block; /* Untuk mencegah extra space */ }

        .main-scrollable-content {
            flex-grow: 1; /* Mengisi ruang antara header dan footer */
            overflow-y: auto; /* Ini yang akan scroll */
            position: relative; /* Konteks untuk elemen absolut jika ada */
        }

        .content-wrapper {
            padding: 25px;
        }
        body.chat-active-page .content-wrapper { /* Class spesifik jika ada halaman chat full-screen */
            padding: 0;
        }

        .footer-dashboard {
            padding: 15px;
            text-align: center;
            background: var(--bg-footer);
            border-top: 1px solid var(--border-sidebar-internal);
            font-size: 0.9rem;
            color: var(--color-text-sidebar-muted);
            flex-shrink: 0; /* Tidak menyusut */
            position: sticky; /* Menempel di bawah .page-content-wrapper */
            bottom: 0;
            z-index: 890;
        }

        .card {
            background: var(--bg-card);
            color: var(--text-default);
            border-radius: 12px;
            border: 1px solid var(--border-sidebar-internal);
            box-shadow: 0 3px 10px rgba(93, 64, 55, 0.07);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(93, 64, 55, 0.1);
        }

        .btn-primary {
            background-color: var(--btn-primary-bg);
            color: var(--btn-primary-text);
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
        }
        .btn-primary:hover {
            background-color: var(--btn-primary-hover-bg);
        }

        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); z-index: 999; display: none; opacity: 0; transition: opacity 0.3s ease-in-out; }
        body.sidebar-open-mobile .sidebar-overlay { display: block; opacity: 1; }

        /* === PAGINATION STYLING (TANPA BOX, TEKS COKLAT) === */
        /* 1. PAGINATION STANDAR LARAVEL */
        .pagination { display: flex; flex-wrap: wrap; justify-content: center; padding-left: 0; list-style: none; margin-top: 1.5rem; margin-bottom: 1.5rem; }
        .page-item { margin: 0 3px; }
        .page-item .page-link {
            position: relative;
            display: block;
            padding: 0.5em 0.85em;
            font-size: 0.9rem;
            line-height: 1.4;
            color: var(--color-text-sidebar-default); /* COKLAT TUA untuk teks link */
            background-color: transparent;
            border: none;
            box-shadow: none;
            text-decoration: none;
            border-radius: 4px;
            transition: color 0.15s ease-in-out, text-decoration 0.15s ease-in-out;
        }
        .page-item .page-link:hover,
        .page-item .page-link:focus {
            z-index: 2;
            color: var(--color-text-sidebar-default); /* Tetap COKLAT TUA saat hover */
            background-color: transparent;
            text-decoration: underline; /* Garis bawah saat hover/focus */
        }
        .page-item.active .page-link {
            z-index: 3;
            color: var(--color-text-sidebar-default); /* COKLAT TUA untuk yang aktif */
            font-weight: 700;
            background-color: transparent;
            text-decoration: none;
        }
        .page-item.active .page-link:hover,
        .page-item.active .page-link:focus {
            text-decoration: none; /* Aktif tidak perlu underline */
        }
        .page-item.disabled .page-link {
            color: var(--color-text-sidebar-muted); /* COKLAT MUDA untuk disabled */
            pointer-events: none;
            background-color: transparent;
        }

        /* 2. STYLING UNTUK KONTEN SPESIFIK HALAMAN (MISAL: DESAIN SAYA) */
        /* Judul Halaman (Contoh: "Desain 3D Saya") */
        .content-wrapper .flex.justify-between.items-center > h1.text-2xl.font-bold {
            color: var(--text-on-sidebar); /* Warna coklat tua */
        }

        /* Penyesuaian spesifik untuk Kartu Desain di halaman tertentu */
        .card .bg-gray-200 { /* Placeholder gambar di dalam card */
            background-color: #FFFDE7 !important; /* Kuning sangat muda, sedikit lebih pekat */
            height: 12rem; /* Tailwind h-48 */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .card .bg-gray-200 > svg { /* Ikon placeholder gambar */
            color: var(--color-text-sidebar-muted) !important; /* Coklat muda */
            width: 4rem; /* Tailwind w-16 */
            height: 4rem; /* Tailwind h-16 */
        }

        /* Teks di dalam konten card (yang menggunakan class text-gray-500) */
        .card .p-4 h3.font-medium { /* Judul Desain di card */
            color: var(--text-default); /* Warna teks default dari .card (coklat tua gelap) */
            font-weight: 500; /* Tailwind font-medium */
        }
        .card .p-4 p.text-gray-500,
        .card .p-4 span.text-gray-500 {
            color: var(--color-text-sidebar-muted) !important; /* Coklat muda */
        }

        /* Tombol Aksi Ikon di dalam card (View, Edit, Delete) */
        .card .p-4 .flex.space-x-2 button.text-gray-500 { /* Target tombol spesifik */
            color: var(--color-text-sidebar-muted) !important; /* Warna ikon coklat muda */
        }
        /* Tombol aksi saat di-hover tetap menggunakan aksen oranye tua (--primary-dark) */
        .card .p-4 .flex.space-x-2 button.text-gray-500:hover {
            color: var(--primary-dark) !important;
        }
        /* Jika tombol aksi adalah <a>, gunakan selector yang sesuai */
        .card .p-4 .flex.space-x-2 a.text-gray-500 {
            color: var(--color-text-sidebar-muted) !important;
        }
        .card .p-4 .flex.space-x-2 a.text-gray-500:hover {
            color: var(--primary-dark) !important;
        }


        /* PAGINATION KUSTOM di Halaman Desain (TANPA BOX, TEKS COKLAT) */
        /* Menargetkan struktur HTML pagination dari contoh designs.blade.php */
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex.rounded-md.shadow {
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex a.px-3.py-2 {
            background-color: transparent !important;
            border: none !important;
            color: var(--color-text-sidebar-default) !important; /* COKLAT TUA untuk teks link */
            margin: 0 2px;
            text-decoration: none;
            border-radius: 4px !important;
            line-height: 1.5;
            /* padding sudah diatur oleh px-3 py-2 Tailwind */
        }
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex a.px-3.py-2:hover,
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex a.px-3.py-2:focus {
            color: var(--color-text-sidebar-default) !important; /* Tetap COKLAT TUA saat hover */
            background-color: transparent !important;
            text-decoration: underline; /* Garis bawah saat hover/focus */
        }
        /* Link pagination kustom yang aktif (berdasarkan class text-orange-600 di HTML Anda) */
        /* Class 'text-orange-600' pada link aktif dari HTML kustom Anda akan di-override di sini */
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex a.text-orange-600 {
            color: var(--color-text-sidebar-default) !important; /* COKLAT TUA untuk aktif */
            font-weight: bold !important;
            text-decoration: none;
        }
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex a.text-orange-600:hover,
        .content-wrapper .mt-6.flex.justify-center nav.inline-flex a.text-orange-600:focus {
            text-decoration: none; /* Aktif tidak perlu underline */
        }


        /* --- RESPONSIVE SIDEBAR & LAYOUT --- */
        @media (min-width: 769px) {
            .hamburger-button { display: inline-flex; }
            .dashboard-container.sidebar-collapsed .sidebar {
                width: 0;
                padding-left: 0;
                padding-right: 0;
                border-right: none;
                overflow: hidden;
            }
            .dashboard-container.sidebar-collapsed .sidebar .app-logo,
            .dashboard-container.sidebar-collapsed .sidebar nav a span, /* Jika ada span di dalam link menu */
            .dashboard-container.sidebar-collapsed .sidebar .sidebar-footer {
                opacity: 0;
                visibility: hidden;
            }
            .dashboard-container.sidebar-collapsed .sidebar nav a svg {
                margin-right: 0; /* Atau sesuaikan agar ikon tetap di tengah jika perlu */
            }
            /* Saat sidebar desktop collapsed, .page-content-wrapper tidak perlu margin-left */
            .dashboard-container.sidebar-collapsed .page-content-wrapper {
                margin-left: 0; 
            }
             .dashboard-container:not(.sidebar-collapsed) .page-content-wrapper {
                /* margin-left: var(--sidebar-width-desktop); /* Tidak perlu jika sidebar ada dalam flow */
            }
        }

        @media (max-width: 768px) {
            /* Sidebar mobile menjadi fixed overlay */
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%); /* Default tersembunyi */
                width: var(--sidebar-width-mobile);
                /* box-shadow: 2px 0 10px rgba(0,0,0,0.1); (Sudah ada) */
            }
            body.sidebar-open-mobile .sidebar {
                transform: translateX(0%); /* Muncul */
            }
            /* page-content-wrapper mengambil lebar penuh di mobile, tidak ada margin-left */
            .page-content-wrapper {
                width: 100%;
                margin-left: 0 !important;
            }
            .hamburger-button { /* Selalu tampilkan hamburger di mobile */
                display: inline-flex;
            }
            .header-dashboard h1.header-title { font-size: 1.1rem; }
            .header-dashboard { padding: 0 15px; }
            .content-wrapper { padding: 15px; }
        }
        @media (max-width: 480px) {
            .header-dashboard h1.header-title { font-size: 1rem; }
            .content-wrapper { padding: 10px; }
        }

    </style>
    @stack('styles')
</head>
<body class="@yield('body-class')">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-container" id="dashboardContainer">
        <!-- @include('components.dashboard.sidebar') -->
        @auth
            @if(Auth::user()->isAdmin())
                @include('components.admin.sidebar')
            @else
                @include('components.dashboard.sidebar')
            @endif
        @endauth
        <div class="page-content-wrapper">
            @include('components.dashboard.header')

            <div class="main-scrollable-content">
                <main class="content-wrapper">
                    @yield('content')
                </main>
            </div>

            @hasSection('custom-footer')
                @yield('custom-footer')
            @else
                @unless(View::hasSection('hide-default-footer'))
                <footer class="footer-dashboard">
                    &copy; {{ date('Y') }} PrintLab 3D. All rights reserved.
                </footer>
                @endunless
            @endif
        </div>
    </div>

    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const hamburgerButton = document.getElementById('hamburger-button');
        const sidebar = document.getElementById('sidebar');
        const dashboardContainer = document.getElementById('dashboardContainer');
        // const pageContentWrapper = document.querySelector('.page-content-wrapper'); // Tidak lagi diubah marginnya via JS untuk desktop
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const body = document.body;
        const iconMenu = hamburgerButton ? hamburgerButton.querySelector('.icon-menu') : null;
        const iconClose = hamburgerButton ? hamburgerButton.querySelector('.icon-close') : null;

        function updateHamburgerIconVisibility(isOpen) {
            if (!iconMenu || !iconClose) return;
            if (isOpen) {
                iconMenu.style.display = 'none';
                iconClose.style.display = 'inline-block';
            } else {
                iconMenu.style.display = 'inline-block';
                iconClose.style.display = 'none';
            }
        }

        function toggleSidebar() {
            if (!hamburgerButton || !sidebar) return;

            const isMobile = window.innerWidth <= 768;
            let isOpen;

            if (isMobile) {
                body.classList.toggle('sidebar-open-mobile');
                isOpen = body.classList.contains('sidebar-open-mobile');
                if (isOpen) {
                    if(sidebarOverlay) sidebarOverlay.style.display = 'block';
                    requestAnimationFrame(() => { if(sidebarOverlay) sidebarOverlay.style.opacity = '1'; });
                    // Sidebar transform akan diatur oleh class .sidebar-open-mobile .sidebar
                } else {
                    if(sidebarOverlay) sidebarOverlay.style.opacity = '0';
                    if(sidebarOverlay) sidebarOverlay.addEventListener('transitionend', () => {
                       if (!body.classList.contains('sidebar-open-mobile')) {
                            if(sidebarOverlay) sidebarOverlay.style.display = 'none';
                       }
                    }, { once: true });
                }
            } else { // Desktop
                dashboardContainer.classList.toggle('sidebar-collapsed');
                isOpen = !dashboardContainer.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsedDesktop', !isOpen); // true jika collapsed, false jika open
            }
            if (hamburgerButton) hamburgerButton.setAttribute('aria-expanded', isOpen.toString());
            updateHamburgerIconVisibility(isOpen);
        }

        if (hamburgerButton && sidebar) {
            hamburgerButton.addEventListener('click', toggleSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                if (body.classList.contains('sidebar-open-mobile')) {
                    toggleSidebar();
                }
            });
        }

        function initializeSidebarState() {
            if (!dashboardContainer) return;
            const isMobile = window.innerWidth <= 768;
            let sidebarActuallyOpenOrVisible;

            if (isMobile) {
                body.classList.remove('sidebar-open-mobile'); // Mulai dengan sidebar mobile tertutup
                dashboardContainer.classList.remove('sidebar-collapsed'); // Hapus state desktop
                // CSS akan menangani transform: translateX(-100%) untuk .sidebar di mobile
                sidebarActuallyOpenOrVisible = false;
            } else { // Desktop
                // Hapus class mobile jika ada
                body.classList.remove('sidebar-open-mobile');
                if(sidebarOverlay) {
                    sidebarOverlay.style.opacity = '0';
                    sidebarOverlay.style.display = 'none';
                }

                // Terapkan state collapsed dari localStorage
                const shouldBeOpenDesktop = localStorage.getItem('sidebarCollapsedDesktop') === 'false';
                if (shouldBeOpenDesktop) {
                    dashboardContainer.classList.remove('sidebar-collapsed');
                    sidebarActuallyOpenOrVisible = true;
                } else { // Default atau jika localStorage bilang collapsed (value 'true' atau null)
                    dashboardContainer.classList.add('sidebar-collapsed');
                    sidebarActuallyOpenOrVisible = false;
                }
            }

            if (hamburgerButton) {
                hamburgerButton.style.display = 'inline-flex'; // Hamburger selalu tampil
                hamburgerButton.setAttribute('aria-expanded', sidebarActuallyOpenOrVisible.toString());
                updateHamburgerIconVisibility(sidebarActuallyOpenOrVisible);
            }
        }

        initializeSidebarState();

        window.addEventListener('resize', function() {
            initializeSidebarState(); // Selalu re-initialize untuk menangani semua perubahan state
        });
    });
    </script>
</body>
</html>