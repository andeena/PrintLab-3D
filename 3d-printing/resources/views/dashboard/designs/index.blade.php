@extends('dashboard')

@section('title', 'Galeri Desain')

@section('content')
{{-- Header Halaman dan Form Pencarian --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Galeri Desain</h1>
        <p class="text-sm text-gray-500 mt-1">Jelajahi koleksi desain publik yang tersedia untuk dipesan.</p>
    </div>
    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('designs.index') }}" class="w-full sm:w-auto">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama desain..."
                   class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
            <button type="submit" class="absolute top-0 right-0 p-2.5 text-gray-400 hover:text-orange-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>
    </form>
</div>

{{-- Menampilkan hasil pencarian jika ada --}}
@if (request('search') && $designs->count() > 0)
    <div class="mb-6 text-sm text-gray-600">
        Menampilkan hasil untuk: <span class="font-semibold">{{ request('search') }}</span>
    </div>
@endif

@if($designs->isNotEmpty())
{{-- Grid untuk Kartu Desain --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($designs as $design)
    <div class="card p-0 overflow-hidden flex flex-col group">
        {{-- Bagian Gambar --}}
        <a href="{{ route('designs.show', $design->id) }}" class="block">
            <div class="h-48 w-full relative overflow-hidden bg-slate-200">
                {{-- FIX: Menggunakan asset() helper dengan fallback yang aman --}}
                <img src="{{ $design->file_path ? asset('storage/' . $design->file_path) : asset('images/placeholder_design.png') }}"
                     alt="Gambar Desain {{ $design->name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out">
            </div>
        </a>
        {{-- Bagian Konten Kartu --}}
        <div class="p-4 flex flex-col flex-grow">
            <a href="{{ route('designs.show', $design->id) }}" title="{{ $design->name }}">
                <h3 class="font-semibold text-base text-gray-800 truncate hover:text-orange-600 transition-colors">
                    {{ $design->name }}
                </h3>
            </a>
            <p class="text-xs text-gray-500 mt-1">Oleh: {{ $design->user->name ?? 'Admin' }}</p>

            {{-- Deskripsi singkat --}}
            @if($design->description)
                <p class="text-sm text-gray-600 mt-2 flex-grow">
                    {{ \Illuminate\Support\Str::limit(strip_tags($design->description), 70) }}
                </p>
            @else
                {{-- Ini akan mengisi ruang kosong jika tidak ada deskripsi --}}
                <div class="flex-grow"></div>
            @endif

            {{-- Tombol Aksi --}}
            <div class="mt-4 flex justify-end items-center border-t border-gray-200 pt-3">
                <a href="{{ route('designs.show', $design->id) }}" class="text-sm font-medium text-orange-600 hover:text-orange-800 hover:underline">
                    Lihat Detail &rarr;
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if ($designs->hasPages())
<div class="mt-8">
    {{-- FIX: Menambahkan withQueryString() agar filter pencarian tidak hilang saat pindah halaman --}}
    {{ $designs->withQueryString()->links() }}
</div>
@endif

@else
{{-- Tampilan jika galeri kosong atau hasil pencarian tidak ditemukan --}}
<div class="text-center py-16 card">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
    <h3 class="mt-2 text-lg font-medium text-gray-800">
        @if(request('search'))
            Desain Tidak Ditemukan
        @else
            Galeri Desain Kosong
        @endif
    </h3>
    <p class="mt-1 text-sm text-gray-500">
        @if(request('search'))
            Tidak ada desain yang cocok dengan kata kunci "{{ request('search') }}".
        @else
            Saat ini belum ada desain yang tersedia.
        @endif
    </p>
</div>
@endif
@endsection