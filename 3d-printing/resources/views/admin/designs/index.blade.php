@extends('dashboard')

@section('title', 'Manajemen Desain')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold">Manajemen Desain</h1>
    <a href="{{ route('admin.designs.create') }}" class="btn-primary flex items-center whitespace-nowrap">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Desain Global
    </a>
</div>

{{-- FORM PENCARIAN --}}
<form method="GET" action="{{ route('admin.designs.index') }}" class="mb-6">
    <div class="flex flex-col sm:flex-row gap-2">
        <input type="text" name="search" placeholder="Cari nama atau keterangan desain..."
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
               value="{{ $searchQuery ?? '' }}">
        <div class="flex space-x-2">
            <button type="submit" class="btn-primary px-6 whitespace-nowrap">
                <svg class="w-5 h-5 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                Cari
            </button>
            @if(isset($searchQuery) && $searchQuery)
                <a href="{{ route('admin.designs.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap flex items-center shadow-sm">
                   Reset
                </a>
            @endif
        </div>
    </div>
</form>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if($designs->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($designs as $design)
    <div class="card overflow-hidden flex flex-col">
        <div class="h-48 w-full relative overflow-hidden bg-gray-200">
            {{-- Menggunakan accessor design_file_url dari Model Design --}}
            <img src="{{ $design->design_file_url }}"
                 alt="Gambar Desain {{ $design->name }}"
                 class="absolute inset-0 w-full h-full object-cover">
        </div>
        <div class="p-4 flex flex-col flex-grow">
            <h3 class="font-semibold text-lg truncate" title="{{ $design->name }}">
                {{ $design->name }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                {{-- Menampilkan pemilik desain --}}
                Oleh: <span class="font-medium">{{ $design->user->name ?? 'Global (Admin)' }}</span>
            </p>

            @if($design->description)
                <p class="text-sm text-gray-600 mt-2 description-preview flex-grow">
                    {{ \Illuminate\Support\Str::limit(strip_tags($design->description), 50) }}
                </p>
            @else
                 <div class="flex-grow"></div> {{-- Spacer jika tidak ada deskripsi --}}
            @endif

            <div class="mt-4 flex justify-end items-center border-t pt-3">
                <div class="flex space-x-1">
                    {{-- Tombol Aksi Admin --}}
                    <a href="{{ route('admin.designs.show', $design->id) }}" class="p-1 text-gray-500 hover:text-orange-500 rounded-full hover:bg-orange-100 transition-colors" title="Lihat Detail">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="{{ route('admin.designs.edit', $design->id) }}" class="p-1 text-gray-500 hover:text-blue-500 rounded-full hover:bg-blue-100 transition-colors" title="Edit Desain">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('admin.designs.destroy', $design->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus desain \'{{ addslashes($design->name) }}\'?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 text-gray-500 hover:text-red-500 rounded-full hover:bg-red-100 transition-colors" title="Hapus Desain">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if ($designs->hasPages())
<div class="mt-8">
    {{ $designs->links() }}
</div>
@endif

@else
    @if (isset($searchQuery) && !empty($searchQuery))
        <div class="text-center py-12 card">
            <h3 class="mt-2 text-lg font-medium text-gray-700">Tidak ada hasil untuk "<span class="font-semibold">{{ $searchQuery }}</span>"</h3>
            <p class="mt-1 text-sm text-gray-500">Coba gunakan kata kunci lain.</p>
        </div>
    @else
        <div class="text-center py-12 card">
            <h3 class="mt-2 text-lg font-medium text-gray-700">Belum ada desain di sistem.</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai tambahkan desain pertama Anda.</p>
        </div>
    @endif
@endif

@endsection