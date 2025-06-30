@extends('dashboard')

@section('title', 'Upload Material Baru') {{-- DIUBAH --}}

@section('content')
    <h1 class="text-2xl font-bold mb-6">Upload Material Baru</h1> {{-- DIUBAH --}}

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow" role="alert">
            <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form action diubah ke route 'materials.store' --}}
    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="card p-6">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Material <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-xs text-gray-500">(Opsional, isi 0 jika gratis)</span></label>
            <input type="number" name="price" id="price" value="{{ old('price', 0) }}" min="0" step="1000"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('price') border-red-500 @enderror">
            @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Gambar Material (JPG, PNG, WEBP) <span class="text-red-500">*</span></label>
            <input type="file" name="file" id="file" required accept="image/jpeg,image/png,image/webp"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-600 hover:file:bg-orange-200 @error('file') border-red-500 @enderror">
            @error('file') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            <img id="file-preview" src="#" alt="Preview Gambar" class="mt-3 max-h-40 h-auto w-auto hidden rounded border p-1 bg-gray-50"/>
        </div>

        <div class="mb-6">
            <label for="is_public" class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                <span class="ml-2 text-sm text-gray-700">Publikasikan material ini?</span>
            </label>
            @error('is_public') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center space-x-4">
            <button type="submit" class="btn-primary">
                Upload Material
            </button>
            <a href="{{ route('materials.index') }}" class="text-sm text-gray-600 hover:text-orange-500">Batal</a> {{-- DIUBAH --}}
        </div>
    </form>
    
    <script>
        const mainFileInput = document.getElementById('file');
        const mainFilePreview = document.getElementById('file-preview');
        if (mainFileInput && mainFilePreview) {
            mainFileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mainFilePreview.src = e.target.result;
                        mainFilePreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    mainFilePreview.src = "#";
                    mainFilePreview.classList.add('hidden');
                }
            });
        }
    </script>
@endsection