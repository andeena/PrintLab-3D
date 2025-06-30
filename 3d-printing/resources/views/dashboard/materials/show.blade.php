@extends('dashboard')

@section('title', 'Detail Material: ' . $material->name)

@section('content')
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <div>
        {{-- Breadcrumb untuk navigasi --}}
        <nav class="text-sm font-medium mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('materials.index') }}" class="text-gray-500 hover:text-orange-500 transition-colors">Katalog Material</a>
                </li>
                <li class="flex items-center mx-2">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </li>
                <li class="flex items-center">
                    {{-- Batasi panjang nama material di breadcrumb jika terlalu panjang --}}
                    <span class="text-gray-800 truncate" style="max-width: 200px;">{{ $material->name }}</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold">Detail Material</h1>
    </div>
    <a href="{{ route('materials.index') }}" class="text-sm text-gray-600 hover:text-orange-500 self-start pt-1">&larr; Kembali ke Katalog</a>
</div>

<div class="card p-0 overflow-hidden">
    <div class="md:flex">
        {{-- Kolom Kiri: Gambar Material --}}
        <div class="md:w-1/2 lg:w-2/5 xl:w-1/3 flex-shrink-0 bg-gray-200">
            <img src="{{ $material->thumbnail_url }}" alt="Thumbnail {{ $material->name }}" class="w-full h-64 md:h-full object-cover">
        </div>
        
        {{-- Kolom Kanan: Detail Informasi --}}
        <div class="p-6 md:p-8 flex flex-col flex-grow">
            <h2 class="text-3xl font-bold mb-1 text-gray-900">{{ $material->name }}</h2>
            
            <p class="text-sm text-gray-500 mb-4">
                Disediakan oleh: <span class="font-medium">{{ $material->user->name ?? 'Admin' }}</span>
            </p>

            @if($material->price > 0)
            <p class="text-2xl font-bold text-orange-600 mb-4">
                Harga: Rp {{ number_format($material->price, 0, ',', '.') }}
            </p>
            @elseif(isset($material->price) && $material->price == 0)
            <p class="text-xl font-semibold text-green-600 mb-4">
                Harga Kustom (Hubungi untuk info)
            </p>
            @endif

            <h4 class="font-semibold text-gray-800 mt-4 mb-2">Deskripsi:</h4>
            <div class="prose prose-sm max-w-none text-gray-600">
                 {!! nl2br(e($material->description)) ?: '<p><em>Tidak ada deskripsi untuk material ini.</em></p>' !!}
            </div>

            {{-- Spacer untuk mendorong tombol ke bawah --}}
            <div class="flex-grow"></div>

            {{-- Tombol Aksi Utama untuk User: Pesan Material Ini --}}
            <div class="mt-8 pt-6 border-t">
                <a href="{{ route('orders.create', ['material_id' => $material->id]) }}" class="btn-primary inline-flex items-center text-base py-3 px-6">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H4.72l-.21-1.054A1 1 0 003 1H2a1 1 0 000 2h1zM6 16a1 1 0 11-2 0 1 1 0 012 0zm10 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    Pesan Material Ini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection