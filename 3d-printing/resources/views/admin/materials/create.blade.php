@extends('dashboard')

@section('title', 'Tambah Material Baru')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Material Global Baru</h1>
    <p class="text-sm text-gray-500 mb-4">Material yang ditambahkan di sini akan bisa dilihat dan dipilih oleh semua pengguna saat membuat pesanan.</p>

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

    <form action="{{ route('admin.materials.store') }}" method="POST" enctype="multipart/form-data" class="card p-6">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Material <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        {{-- BAGIAN DESKRIPSI YANG DITAMBAHKAN --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
            <textarea name="description" id="description" rows="4" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        {{-- AKHIR BAGIAN DESKRIPSI --}}
        
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga per Unit (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="price" id="price" value="{{ old('price', 0) }}" required min="0" step="1000"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('price') border-red-500 @enderror">
            @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Gambar Material <span class="text-red-500">*</span></label>
            <input type="file" name="file" id="file" required accept="image/jpeg,image/png,image/webp,gif"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200 @error('file') border-red-500 @enderror">
            @error('file') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        
        {{-- Admin tidak perlu set status publik/privat untuk material global, kita anggap selalu publik --}}

        <div class="flex items-center space-x-4 mt-6">
            <button type="submit" class="btn-primary">Simpan Material</button>
            <a href="{{ route('admin.materials.index') }}" class="text-sm text-gray-600 hover:text-orange-500">Batal</a>
        </div>
    </form>
@endsection