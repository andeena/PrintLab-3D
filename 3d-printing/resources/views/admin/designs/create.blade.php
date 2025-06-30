@extends('dashboard')

@section('title', 'Tambah Desain Baru')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Tambah Desain Global Baru</h1>
    <a href="{{ route('admin.designs.index') }}" class="text-sm text-gray-600 hover:text-orange-500">&larr; Kembali ke Manajemen Desain</a>
</div>

<p class="text-sm text-gray-500 mb-4">
    Desain yang ditambahkan di sini akan bisa dilihat dan dipilih oleh semua pengguna saat mereka membuat pesanan baru.
</p>

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow" role="alert">
        <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.designs.store') }}" method="POST" enctype="multipart/form-data" class="card p-6 md:p-8">
    @csrf
    
    {{-- Nama Desain --}}
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Desain <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
        @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    {{-- Keterangan Desain --}}
    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Desain (Opsional)</label>
        <textarea name="description" id="description" rows="4" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
        @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    {{-- File Gambar Desain --}}
    <div class="mb-6">
        <label for="design_file" class="block text-sm font-medium text-gray-700 mb-1">File Gambar Desain (JPG, PNG, WEBP) <span class="text-red-500">*</span></label>
        <input type="file" name="design_file" id="design_file" required accept="image/jpeg,image/png,image/webp,gif"
               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200 @error('design_file') border-red-500 @enderror">
        @error('design_file') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        
        {{-- Preview Gambar --}}
        <img id="image-preview" src="#" alt="Preview Gambar" class="mt-4 max-h-60 h-auto w-auto hidden rounded border p-1 bg-gray-50"/>
    </div>
    
    <div class="flex items-center space-x-4 border-t pt-5">
        <button type="submit" class="btn-primary">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>
            Simpan Desain
        </button>
        <a href="{{ route('admin.designs.index') }}" class="text-sm text-gray-600 hover:text-orange-500">Batal</a>
    </div>
</form>

<script>
    const imageInput = document.getElementById('design_file');
    const imagePreview = document.getElementById('image-preview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "#";
                imagePreview.classList.add('hidden');
            }
        });
    }
</script>
@endsection