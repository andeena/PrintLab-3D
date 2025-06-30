@extends('dashboard')

@section('title', 'Dashboard')

@section('content')

{{-- Bagian 1: Kartu Statistik Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    {{-- Card Total Material --}}
    <div class="card p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Material</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_materials'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- Card Pesanan Aktif --}}
    <div class="card p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pesanan Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['active_orders'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- Card Total Pengeluaran --}}
    <div class="card p-5">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_spending'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Bagian 2: Katalog Material --}}
<div class="card p-6 mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-700">Katalog Material</h2>
        @if(isset($latestMaterials) && $latestMaterials->isNotEmpty())
            <a href="{{ route('materials.index') }}" class="text-orange-500 hover:underline text-sm font-medium">Lihat Semua</a>
        @endif
    </div>

    @if(isset($latestMaterials) && $latestMaterials->isNotEmpty())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($latestMaterials as $material)
        <div class="border border-gray-200 rounded-lg overflow-hidden group transition-shadow hover:shadow-lg">
            <a href="{{ route('materials.show', $material->id) }}" class="block">
                <div class="h-40 w-full relative overflow-hidden bg-gray-100">
                    <img src="{{ $material->thumbnail_url ?? asset('images/placeholder.png') }}"
                         alt="Thumbnail {{ $material->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
            </a>
            <div class="p-4 bg-white">
                <a href="{{ route('materials.show', $material->id) }}" title="{{ $material->name }}">
                    <h3 class="font-semibold text-base truncate text-gray-800 hover:text-orange-600 transition-colors">
                        {{ $material->name }}
                    </h3>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-10 border-2 border-dashed rounded-lg">
        <p class="mt-2 text-sm text-gray-500">Belum ada material publik di katalog.</p>
    </div>
    @endif
</div>


{{-- ======================================================= --}}
{{-- BAGIAN PESANAN TERBARU ANDA (BAGIAN YANG DIPERBAIKI) --}}
{{-- ======================================================= --}}
<div class="card p-0 overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">Pesanan Terbaru Anda</h2>
            @if(isset($latestOrders) && $latestOrders->isNotEmpty())
                <a href="{{ route('orders.index') }}" class="text-orange-500 hover:underline text-sm font-medium">Lihat Semua Pesanan</a>
            @endif
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Utama</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($latestOrders as $order)
                <tr class="hover:bg-yellow-50 transition-colors">
                    {{-- ID Pesanan & Tanggal --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->translatedFormat('d M Y') }}</div>
                    </td>

                    {{-- FIX: Kolom Item sekarang menampilkan item pertama dan jumlah item lainnya --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($firstItem = $order->items->first())
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-md object-cover bg-gray-200" src="{{ $firstItem->material->thumbnail_url ?? asset('images/placeholder.png') }}" alt="{{ $firstItem->material_name }}">
                            </div>
                            <div class="ml-4">
                                <div class="font-medium text-gray-900">{{ $firstItem->design_name ?? 'Pesanan Kustom' }}</div>
                                <div class="text-gray-500">
                                    {{ $firstItem->material_name }}
                                    @if($order->items->count() > 1)
                                        <span class="text-blue-500 font-medium">(+{{ $order->items->count() - 1 }} item lain)</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                        <span class="text-gray-500 italic">Tidak ada item</span>
                        @endif
                    </td>

                    {{-- Status Pesanan --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php $statusClasses = ['pending' => 'bg-yellow-100 text-yellow-800','processing' => 'bg-blue-100 text-blue-800','shipped' => 'bg-indigo-100 text-indigo-800','completed' => 'bg-green-100 text-green-800','cancelled' => 'bg-red-100 text-red-800']; @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[strtolower($order->status)] ?? 'bg-gray-100 text-gray-800' }}">{{ ucfirst($order->status) }}</span>
                    </td>

                    {{-- Total Harga --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-900">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    
                    {{-- Aksi --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-900 hover:underline">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <p>Anda belum memiliki pesanan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

