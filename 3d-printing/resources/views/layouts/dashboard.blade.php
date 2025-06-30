<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }
        .app-layout {
            display: flex;
            height: 100vh;
        }
        .app-main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .app-header {
            flex-shrink: 0;
            height: 65px;
        }
        .app-content {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem; /* Padding default untuk halaman biasa */
            background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
            position: relative;
        }
        /* PERBAIKAN KUNCI DI SINI: Class ini akan menghapus padding */
        .app-content.no-padding {
            padding: 0;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100">

    <div class="app-layout">
        @include('components.admin.sidebar')

        <div class="app-main-content">
            <header class="app-header bg-white border-b flex items-center justify-between px-6">
                <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
                <div class="text-gray-700">Admin PrintLab</div>
            </header>

            {{-- 
                PERBAIKAN KUNCI DI SINI:
                Secara dinamis menambahkan class 'no-padding' jika rute saat ini adalah rute chat.
            --}}
            <main class="app-content @if(request()->routeIs('admin.chat.*')) no-padding @endif">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>