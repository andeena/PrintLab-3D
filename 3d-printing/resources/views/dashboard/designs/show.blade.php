@extends('dashboard')

@section('title', 'Detail Desain: ' . $design->name)

@section('content')
{{-- Header Halaman --}}
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Desain</h1>
        <p class="text-sm text-gray-500 mt-1">Melihat rincian untuk: <span class="font-medium text-gray-700">{{ $design->name }}</span></p>
    </div>
    <div>
        <a href="{{ route('designs.index') }}" class="text-sm font-medium text-gray-600 hover:text-orange-500 transition-colors">&larr; Kembali ke Daftar Desain</a>
    </div>
</div>

{{-- Card Utama --}}
<div class="card p-0 overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-5">
        
        {{-- Kolom Kiri untuk Gambar --}}
        <div class="md:col-span-2 bg-slate-100 p-4 flex items-center justify-center">
            @if($design->design_file_url)
                <a href="{{ $design->design_file_url }}" target="_blank" title="Lihat gambar ukuran penuh">
                    <img src="{{ $design->design_file_url }}" 
                         alt="Gambar {{ $design->name }}" 
                         class="max-w-full max-h-[400px] object-contain rounded-lg bg-white shadow-md">
                </a>
            @else
                {{-- Tampilan Placeholder jika tidak ada gambar --}}
                <div class="w-full h-full flex items-center justify-center bg-slate-200 rounded-lg aspect-square">
                    <svg class="w-20 h-20 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan untuk Informasi & Aksi --}}
        <div class="md:col-span-3 p-6 md:p-8 flex flex-col">
            
            {{-- Header Informasi --}}
            <div>
                <div class="flex justify-between items-start">
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">{{ $design->name }}</h2>
                    <span class="text-xs px-2 py-1 rounded-full shadow-sm {{ $design->is_public ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $design->is_public ? 'Publik' : 'Privat' }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-4">
                    Oleh: {{ $design->user->name ?? 'N/A' }} | Diperbarui: {{ $design->updated_at->format('d M Y') }}
                </p>
            </div>
            
            <hr class="my-4">

            {{-- Keterangan --}}
            <div class="flex-grow">
                <h4 class="font-semibold text-gray-700 mb-2">Keterangan:</h4>
                <div class="prose prose-sm max-w-none text-gray-600">
                    {!! nl2br(e($design->description)) ?: '<p class="italic">Tidak ada keterangan yang diberikan.</p>' !!}
                </div>
            </div>

            {{-- Bagian Aksi (Edit, Hapus, Download) --}}
            <div class="mt-auto pt-6 flex flex-wrap items-center gap-3 border-t border-gray-200">
                @if($design->file_path)
                    {{-- FIX: Link download yang lebih aman dan benar --}}
                    <a href="{{ route('designs.download', $design->id) }}" {{-- Asumsi ada route bernama 'designs.download' --}}
                       class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download File Desain
                    </a>
                @endif
                
                {{-- Tombol Edit & Hapus --}}
                @can('update', $design)
                    <a href="{{ route('designs.edit', $design->id) }}" class="btn-secondary">
                        Edit
                    </a>
                @endcan
                @can('delete', $design)
                    <form action="{{ route('designs.destroy', $design->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus desain ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
