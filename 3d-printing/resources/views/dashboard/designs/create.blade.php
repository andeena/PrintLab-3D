@extends('dashboard')
@section('title', 'Upload Desain Baru')
@section('content')
    <h1 class="text-2xl font-bold mb-6">Upload Desain Baru</h1>
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow" role="alert">
            <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('designs.store') }}" method="POST" enctype="multipart/form-data" class="card p-6">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Desain <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Desain</label>
            <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label for="design_file" class="block text-sm font-medium text-gray-700 mb-1">File Desain (Gambar: JPG, PNG, WEBP) <span class="text-red-500">*</span></label>
            <input type="file" name="design_file" id="design_file" required accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200 @error('design_file') border-red-500 @enderror">
            @error('design_file') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        <div class="flex items-center space-x-4">
            <button type="submit" class="btn-primary">Upload Desain</button>
            <a href="{{ route('designs.index') }}" class="text-sm text-gray-600 hover:text-orange-500">Batal</a>
        </div>
    </form>
@endsection