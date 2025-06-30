@extends('dashboard')

@section('title', 'Manajemen Pesanan')

@section('content')
<h1 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Semua Pesanan</h1>

{{-- Navigasi Tab Status --}}
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
            @php
                $tabs = ['' => 'Semua', 'pending' => 'Pending', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                $currentStatus = request()->query('status', '');
            @endphp
            @foreach($tabs as $tabStatusValue => $tabLabel)
                <a href="{{ route('admin.orders.index', ['status' => $tabStatusValue]) }}"
                   class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ $currentStatus == $tabStatusValue ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $tabLabel }}
                    @if(isset($statusCounts[$tabStatusValue]))
                        <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-medium {{ $currentStatus == $tabStatusValue ? 'bg-orange-100 text-orange-600' : 'bg-gray-200 text-gray-600' }}">{{ $statusCounts[$tabStatusValue] }}</span>
                    @elseif($tabStatusValue == '' && $statusCounts->sum() > 0)
                        <span class="ml-1.5 rounded-full px-2 py-0.5 text-xs font-medium {{ $currentStatus == $tabStatusValue ? 'bg-orange-100 text-orange-600' : 'bg-gray-200 text-gray-600' }}">{{ $statusCounts->sum() }}</span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>
</div>

{{-- Notifikasi Sukses/Error --}}
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow" role="alert"><p>{{ session('success') }}</p></div>
@endif
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow" role="alert"><p>{{ session('error') }}</p></div>
@endif

{{-- Tabel Pesanan --}}
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Pesanan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Cepat</th>
                    {{-- KOLOM BARU UNTUK DETAIL --}}
                    <th class="relative px-6 py-3"><span class="sr-only">Detail</span></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-yellow-50">
                    {{-- Kolom Pelanggan --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</div>
                        <div class="text-gray-500">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </td>

                    {{-- Kolom Detail Pesanan --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $order->design_name }}</div>
                        <div class="text-gray-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    </td>

                    {{-- Kolom Status Pesanan --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                            $orderStatusClasses = ['pending' => 'bg-yellow-100 text-yellow-800', 'processing' => 'bg-blue-100 text-blue-800', 'shipped' => 'bg-indigo-100 text-indigo-800', 'completed' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-red-100 text-red-800'];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $orderStatusClasses[strtolower($order->status)] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>

                    {{-- Kolom Status Pembayaran & Bukti --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                         @php
                            $paymentStatusClasses = ['unpaid' => 'bg-gray-100 text-gray-800', 'pending_verification' => 'bg-orange-100 text-orange-800', 'paid' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-800', 'cancelled' => 'bg-gray-100 text-gray-800'];
                         @endphp
                         <div class="flex items-center">
                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentStatusClasses[strtolower($order->payment_status)] ?? 'bg-gray-100 text-gray-800' }}">
                                 {{ str_replace('_', ' ', ucfirst($order->payment_status)) }}
                            </span>
                            @if($order->payment_proof)
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="ml-2 text-blue-500 hover:text-blue-700" title="Lihat Bukti Pembayaran">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            @endif
                         </div>
                    </td>
                    
                    {{-- Kolom Aksi Cepat (Verifikasi / Update Status) --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($order->payment_status === 'pending_verification')
                            <div class="flex items-center space-x-2">
                                <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui pembayaran ini?');">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" class="text-xs text-white bg-green-500 hover:bg-green-600 rounded px-2 py-1">Setujui</button>
                                </form>
                                <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menolak pembayaran ini?');">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="payment_status" value="rejected">
                                    <button type="submit" class="text-xs text-white bg-red-500 hover:bg-red-600 rounded px-2 py-1">Tolak</button>
                                </form>
                            </div>
                        @elseif($order->payment_status === 'paid' && !in_array($order->status, ['completed', 'cancelled']))
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center">
                            @csrf @method('PUT')
                            <select name="status" class="w-auto text-xs border-gray-300 rounded-md shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                <option value="processing" @if($order->status == 'processing') selected @endif>Processing</option>
                                <option value="shipped" @if($order->status == 'shipped') selected @endif>Shipped</option>
                                <option value="completed" @if($order->status == 'completed') selected @endif>Completed</option>
                            </select>
                            <button type="submit" class="ml-2 btn-primary" style="padding: 0.35rem 0.5rem; font-size: 0.75rem;">&#10003;</button>
                        </form>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>

                    {{-- KOLOM BARU HANYA UNTUK TOMBOL DETAIL --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-orange-600 hover:text-orange-900 hover:underline">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                {{-- FIX: Colspan diubah menjadi 6 karena ada 6 kolom sekarang --}}
                <tr><td colspan="6" class="text-center py-12 text-gray-500">
                    <p>Tidak ada pesanan dengan status ini.</p>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">{{ $orders->links() }}</div>
    @endif
</div>
@endsection