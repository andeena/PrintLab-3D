@extends('dashboard')

@section('title', 'Edit Material: ' . $material->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Edit Material: <span class="font-normal">{{ $material->name }}</span></h1>
    <a href="{{ route('admin.materials.index') }}" class="text-sm text-gray-600 hover:text-orange-500">&larr; Kembali ke Daftar Material</a>
</div>

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow" role="alert">
        <p class="font-bold">Oops! Ada kesalahan:</p>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" class="card p-6">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Material <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $material->name) }}" required 
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
        @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
        <input type="number" name="price" id="price" value="{{ old('price', $material->price) }}" required min="0" step="1000"
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('price') border-red-500 @enderror">
        @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="description" id="description" rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $material->description) }}</textarea>
        @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar Material (JPG, PNG, WEBP)</label>
        @if($material->file_path)
            <div class="mb-2">
                <p class="text-xs text-gray-500">Gambar saat ini:</p>
                <img src="{{ $material->thumbnail_url }}" alt="Thumbnail {{ $material->name }}" class="mt-1 max-h-40 h-auto w-auto rounded border p-1 bg-gray-50">
            </div>
        @endif
        <input type="file" name="file" id="file" accept="image/jpeg,image/png,image/webp,gif"
               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200 @error('file') border-red-500 @enderror">
        @error('file') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti gambar.</p>
    </div>

    <div class="mb-6">
        <label for="is_public" class="flex items-center cursor-pointer">
            <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', $material->is_public) ? 'checked' : '' }}
                   class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
            <span class="ml-2 text-sm text-gray-700">Publikasikan material ini? (Bisa dipilih semua user)</span>
        </label>
        @error('is_public') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-center space-x-4">
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.materials.index') }}" class="text-sm text-gray-600 hover:text-orange-500">Batal</a>
    </div>
</form>
@endsection