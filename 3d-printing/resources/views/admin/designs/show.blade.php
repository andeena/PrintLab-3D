@extends('dashboard')

@section('title', 'Detail Desain: ' . $design->name)

@section('content')
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold">Detail Desain</h1>
        <p class="text-sm text-gray-500">Melihat detail untuk: <span class="font-medium text-gray-800">{{ $design->name }}</span></p>
    </div>
    <div>
        {{-- Tombol Edit hanya akan muncul jika admin diizinkan oleh policy --}}
        @can('update', $design)
        <a href="{{ route('admin.designs.edit', $design->id) }}" class="btn-primary-outline text-sm mr-2">
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
        @endcan
        <a href="{{ route('admin.designs.index') }}" class="text-sm text-gray-600 hover:text-orange-500">&larr; Kembali ke Daftar</a>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <div class="md:flex">
        {{-- Kolom Kiri: Gambar Desain --}}
        <div class="md:w-1/2 lg:w-2/5 xl:w-1/3 flex-shrink-0 bg-gray-200">
            <img src="{{ $design->design_file_url }}" alt="Gambar Desain {{ $design->name }}" class="w-full h-64 md:h-full object-cover">
        </div>
        
        {{-- Kolom Kanan: Detail Informasi --}}
        <div class="p-6 md:p-8 flex-grow">
            <h2 class="text-3xl font-bold mb-1 text-gray-900">{{ $design->name }}</h2>
            
            {{-- Informasi Pemilik dan Tanggal --}}
            <div class="text-sm text-gray-500 mb-4 border-b pb-4">
                <p>
                    Pemilik: 
                    <span class="font-semibold text-orange-600">{{ $design->user->name ?? 'Global (Admin)' }}</span>
                </p>
                <p>
                    Dibuat: 
                    <span class="font-medium">{{ $design->created_at->translatedFormat('d F Y, H:i') }}</span>
                </p>
                <p>
                    Diperbarui: 
                    <span class="font-medium">{{ $design->updated_at->translatedFormat('d F Y, H:i') }}</span>
                </p>
            </div>

            {{-- Keterangan Desain --}}
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Keterangan:</h4>
                <div class="prose prose-sm max-w-none text-gray-600">
                    @if($design->description)
                        {!! nl2br(e($design->description)) !!}
                    @else
                        <p class="italic">Tidak ada keterangan untuk desain ini.</p>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi Download --}}
            @if($design->file_path)
            <div class="mt-8">
                <a href="{{ Storage::disk('public')->url($design->file_path) }}" 
                   target="_blank" 
                   download="{{ basename($design->file_path) }}"
                   class="btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download File Gambar
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection