@extends('dashboard')

@section('title', 'Detail Material: ' . $material->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Detail Material</h1>
    <div>
        <a href="{{ route('admin.materials.edit', $material->id) }}" class="btn-primary-outline text-sm mr-2">
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
        <a href="{{ route('admin.materials.index') }}" class="text-sm text-gray-600 hover:text-orange-500">&larr; Kembali ke Daftar</a>
    </div>
</div>

<div class="card p-0 overflow-hidden">
    <div class="md:flex">
        <div class="md:w-1/2 lg:w-2/5 xl:w-1/3 flex-shrink-0 bg-gray-200">
            <img src="{{ $material->thumbnail_url }}" alt="Thumbnail {{ $material->name }}" class="w-full h-64 md:h-full object-cover">
        </div>
        <div class="p-6 md:p-8 flex-grow">
            <h2 class="text-3xl font-bold mb-1">{{ $material->name }}</h2>
            <p class="text-sm text-gray-500 mb-4">
                Pemilik: <span class="font-medium">{{ $material->user->name ?? 'Global (Admin)' }}</span> | Diperbarui: {{ $material->updated_at->format('d M Y, H:i') }}
            </p>

            <p class="text-2xl font-bold text-orange-600 mb-4">
                Rp {{ number_format($material->price, 0, ',', '.') }}
            </p>
            
            <div class="mb-4">
                <span class="text-xs px-3 py-1 rounded-full font-semibold
                    {{ $material->is_public ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $material->is_public ? 'Publik' : 'Privat' }}
                </span>
            </div>

            <h4 class="font-semibold text-gray-700 mt-6 mb-2">Deskripsi:</h4>
            <div class="prose prose-sm max-w-none text-gray-600">
                 {!! nl2br(e($material->description)) ?: '<p><em>Tidak ada deskripsi.</em></p>' !!}
            </div>

            <h4 class="font-semibold text-gray-700 mt-6 mb-2">Detail File Gambar:</h4>
            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                <li>Ukuran File: {{ $material->formatted_file_size ?? 'N/A' }}</li>
            </ul>

            @if($material->file_path)
            <div class="mt-8">
                <a href="{{ Storage::disk('public')->url($material->file_path) }}" 
                   target="_blank" 
                   download="{{ basename($material->file_path) }}"
                   class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Gambar
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection