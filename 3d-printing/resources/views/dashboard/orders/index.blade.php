@extends('dashboard')

@section('title', 'Pesanan Saya')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-800">Riwayat Pesanan Saya</h1>
    {{-- Cek jika route ada sebelum menampilkan tombol --}}
    @if(Route::has('orders.create'))
    <a href="{{ route('orders.create') }}" class="btn-primary flex items-center whitespace-nowrap">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Buat Pesanan Baru
    </a>
    @endif
</div>

{{-- BAGIAN NAVIGASI TAB STATUS --}}
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
            @php
                $tabs = [
                    '' => 'Semua', 'pending' => 'Pending', 'processing' => 'Diproses',
                    'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan',
                ];
                $currentStatus = request()->query('status', '');
            @endphp

            @foreach($tabs as $tabStatusValue => $tabLabel)
                <a href="{{ route('orders.index', ['status' => $tabStatusValue]) }}"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors
                          {{ $currentStatus == $tabStatusValue
                             ? 'border-orange-500 text-orange-600'
                             : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $tabLabel }}
                    
                    @if($tabStatusValue == '')
                        @if($statusCounts->sum() > 0)
                        <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-medium 
                              {{ $currentStatus == $tabStatusValue ? 'bg-orange-100 text-orange-600' : 'bg-gray-200 text-gray-600' }}">
                            {{ $statusCounts->sum() }}
                        </span>
                        @endif
                    @elseif(isset($statusCounts[$tabStatusValue]))
                        <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-medium 
                              {{ $currentStatus == $tabStatusValue ? 'bg-orange-100 text-orange-600' : 'bg-gray-200 text-gray-600' }}">
                            {{ $statusCounts[$tabStatusValue] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

{{-- TABEL DAFTAR PESANAN --}}
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-yellow-50 transition-colors">
                    {{-- ID Pesanan & Tanggal --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->translatedFormat('d M Y') }}</div>
                    </td>

                    {{-- FIX: Kolom Item sekarang menampilkan item pertama dan jumlah item lainnya --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($firstItem = $order->items->first())
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                {{-- Asumsi relasi material() ada di model OrderItem --}}
                                <img class="h-10 w-10 rounded-md object-cover" src="{{ $firstItem->material->thumbnail_url ?? asset('images/placeholder.png') }}" alt="{{ $firstItem->material_name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $firstItem->material_name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $firstItem->color }} (x{{ $firstItem->quantity }})
                                </div>
                                @if($order->items->count() > 1)
                                    <div class="text-xs text-blue-500 mt-1">+ {{ $order->items->count() - 1 }} item lainnya</div>
                                @endif
                            </div>
                        </div>
                        @else
                        <span class="text-sm text-gray-500 italic">Tidak ada item</span>
                        @endif
                    </td>

                    {{-- Status Pesanan --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php $statusClasses = ['pending' => 'bg-yellow-100 text-yellow-800','processing' => 'bg-blue-100 text-blue-800','shipped' => 'bg-indigo-100 text-indigo-800','completed' => 'bg-green-100 text-green-800','cancelled' => 'bg-red-100 text-red-800']; @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[strtolower($order->status)] ?? 'bg-gray-100 text-gray-800' }}">{{ ucfirst($order->status) }}</span>
                    </td>

                    {{-- KOLOM BARU: Status Pembayaran --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php $paymentStatusClasses = ['unpaid' => 'bg-gray-100 text-gray-800', 'pending_verification' => 'bg-orange-100 text-orange-800', 'paid' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-800', 'cancelled' => 'bg-gray-100 text-gray-800']; @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentStatusClasses[strtolower($order->payment_status)] ?? 'bg-gray-100 text-gray-800' }}">{{ str_replace('_', ' ', ucfirst($order->payment_status)) }}</span>
                    </td>

                    {{-- Total Harga --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    
                    {{-- Aksi --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-900 hover:underline" title="Lihat Detail Pesanan">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                        <p>Tidak ada pesanan dengan status ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

