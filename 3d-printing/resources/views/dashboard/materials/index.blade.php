@extends('dashboard')

@section('title', 'Katalog Material')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Katalog Material</h1>
    <p class="text-sm text-gray-500">Pilih material yang tersedia untuk pesanan Anda.</p>
</div>

<form method="GET" action="{{ route('materials.index') }}" class="mb-6">
    {{-- ... (Form pencarian bisa tetap ada) ... --}}
</form>

@if($materials->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($materials as $material)
    <div class="card overflow-hidden flex flex-col">
        <a href="{{ route('materials.show', $material->id) }}" class="block group">
            <div class="h-48 w-full relative overflow-hidden bg-gray-200">
                <img src="{{ $material->thumbnail_url }}"
                     alt="Thumbnail {{ $material->name }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out">
            </div>
        </a>
        <div class="p-4 flex flex-col flex-grow">
            <a href="{{ route('materials.show', $material->id) }}">
                <h3 class="font-semibold text-lg truncate hover:text-orange-600 transition-colors" title="{{ $material->name }}">
                    {{ $material->name }}
                </h3>
            </a>

            @if($material->price > 0)
                <p class="text-base font-semibold text-orange-600 mt-1">
                    Rp {{ number_format($material->price, 0, ',', '.') }}
                </p>
            @else
                <p class="text-base font-semibold text-green-600 mt-1">
                    Harga Kustom
                </p>
            @endif

            @if($material->description)
                <p class="text-sm text-gray-600 mt-2 description-preview">
                    {{ \Illuminate\Support\Str::limit(strip_tags($material->description), 70) }}
                </p>
            @endif

            <div class="flex-grow"></div>

            {{-- Tombol aksi disederhanakan, hanya "Lihat Detail" --}}
            <div class="mt-4 flex justify-end items-center border-t pt-3">
                <a href="{{ route('materials.show', $material->id) }}" class="text-sm font-medium text-orange-500 hover:text-orange-700">
                    Lihat Detail &rarr;
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if ($materials->hasPages())
<div class="mt-8">
    {{ $materials->links() }}
</div>
@endif

@else
    <div class="text-center py-12 card">
        <h3 class="mt-2 text-lg font-medium text-gray-700">Katalog Material Kosong</h3>
        <p class="mt-1 text-sm text-gray-500">Saat ini belum ada material yang tersedia dari admin.</p>
    </div>
@endif
@endsection