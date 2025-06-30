@extends('dashboard')

@section('title', 'Detail Pesanan #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@push('styles')
{{-- Sedikit CSS tambahan untuk garis putus-putus pada status tracker --}}
<style>
    .step-line-dashed {
        background-image: linear-gradient(to right, #D1D5DB 60%, transparent 40%);
        background-position: bottom;
        background-size: 10px 2px;
        background-repeat: repeat-x;
    }
</style>
@endpush

@section('content')

{{-- Notifikasi (jika ada) --}}
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow max-w-7xl mx-auto rounded-lg" role="alert">
        <p class="font-bold">Sukses!</p>
        <p>{{ session('success') }}</p>
    </div>
@endif

{{-- Card Utama --}}
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 md:p-8">

            {{-- 1. HEADER CARD: Breadcrumb, Judul, Info Pelanggan --}}
            <header class="flex flex-col sm:flex-row justify-between items-start mb-8">
                <div>
                    <nav class="text-sm font-medium text-gray-500 mb-1">
                        <a href="{{ route('admin.orders.index') }}" class="hover:text-orange-500">Pesanan Saya</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-700">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan</h1>
                </div>
                <div class="text-sm text-gray-500 mt-2 sm:mt-0 sm:text-right">
                    <p class="font-semibold text-gray-800">{{ $order->user->name ?? 'N/A' }}</p>
                    <p>{{ $order->created_at->translatedFormat('d F Y') }}</p>
                </div>
            </header>

            {{-- 2. STATUS TRACKER VISUAL --}}
            @php
                // Logika untuk menentukan langkah aktif pada status tracker
                $statusMap = ['pending' => 1, 'processing' => 2, 'shipped' => 3, 'completed' => 4, 'cancelled' => 0];
                $currentStep = $statusMap[strtolower($order->status)] ?? 0;
            @endphp
            <div class="w-full mb-10">
                <div class="flex items-center justify-between">
                    {{-- Step 1: Dipesan --}}
                    <div class="flex flex-col items-center text-center w-20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 1 ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="mt-2 text-xs font-semibold {{ $currentStep >= 1 ? 'text-orange-600' : 'text-gray-500' }}">Dipesan</p>
                    </div>

                    {{-- Garis --}}
                    <div class="flex-grow h-0.5 {{ $currentStep >= 2 ? 'bg-orange-500' : 'step-line-dashed' }}"></div>

                    {{-- Step 2: Diproses --}}
                    <div class="flex flex-col items-center text-center w-20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 2 ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <p class="mt-2 text-xs font-semibold {{ $currentStep >= 2 ? 'text-orange-600' : 'text-gray-500' }}">Diproses</p>
                    </div>

                    {{-- Garis --}}
                    <div class="flex-grow h-0.5 {{ $currentStep >= 3 ? 'bg-orange-500' : 'step-line-dashed' }}"></div>

                    {{-- Step 3: Dikirim --}}
                    <div class="flex flex-col items-center text-center w-20">
                         <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 3 ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 2h8a1 1 0 001-1zM3 11h10"></path></svg>
                        </div>
                        <p class="mt-2 text-xs font-semibold {{ $currentStep >= 3 ? 'text-orange-600' : 'text-gray-500' }}">Dikirim</p>
                    </div>

                    {{-- Garis --}}
                    <div class="flex-grow h-0.5 {{ $currentStep >= 4 ? 'bg-orange-500' : 'step-line-dashed' }}"></div>

                    {{-- Step 4: Selesai --}}
                    <div class="flex flex-col items-center text-center w-20">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $currentStep >= 4 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="mt-2 text-xs font-semibold {{ $currentStep >= 4 ? 'text-green-600' : 'text-gray-500' }}">Selesai</p>
                    </div>
                </div>
            </div>

            {{-- 3. GRID 2 KOLOM UNTUK KONTEN UTAMA --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Kolom Kiri: Item yang Dipesan --}}
                <div class="bg-slate-50 p-4 rounded-lg border">
                    <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Item yang Dipesan
                    </h3>
                    <div class="flex items-start space-x-4">
                        <img src="{{ $order->linkedMaterial->thumbnail_url ?? asset('images/placeholder.png') }}" alt="{{ $order->design_name }}" class="w-20 h-20 rounded-md object-cover border flex-shrink-0">
                        <div class="text-sm">
                            <p class="font-bold text-gray-800">{{ $order->design_name }}</p>
                            <p class="text-gray-600">Material: {{ $order->material }}</p>
                            <p class="text-gray-600">Warna: {{ $order->color }}</p>
                            <p class="text-gray-600">Jumlah: {{ $order->quantity }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Alamat & Biaya --}}
                <div class="space-y-4">
                    <div class="bg-slate-50 p-4 rounded-lg border">
                        <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Alamat Pengiriman
                        </h3>
                        <div class="text-sm text-gray-700 leading-relaxed">
                            <p class="font-semibold">{{ $order->recipient_name }} ({{ $order->phone_number }})</p>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg border">
                        <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Ringkasan Biaya
                        </h3>
                        <div class="text-sm space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{$order->quantity}} item)</span>
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Biaya Pengiriman</span>
                                <span>Rp 0</span> {{-- Ganti dengan logika biaya kirim jika ada --}}
                            </div>
                            <hr class="my-1">
                            <div class="flex justify-between font-bold text-gray-800 text-base">
                                <span>Total Pembayaran</span>
                                <span class="text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. FOOTER CARD: Status Pembayaran & Aksi --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                @php $paymentStatus = strtolower($order->payment_status); @endphp

                @if($paymentStatus === 'paid')
                <div class="bg-green-100 border border-green-200 text-green-800 text-center font-semibold p-4 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Pembayaran Lunas
                </div>
                @elseif($paymentStatus === 'pending_verification')
                <div class="bg-orange-100 border border-orange-200 text-orange-800 text-center font-semibold p-4 rounded-lg">
                    Menunggu Verifikasi Pembayaran
                </div>
                {{-- Aksi Verifikasi untuk admin --}}
                 <div class="mt-4 flex items-center justify-center space-x-3">
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn-primary" style="background-color: #3B82F6;">Lihat Bukti</a>
                    <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENYETUJUI pembayaran ini?');">
                        @csrf @method('PUT')
                        <input type="hidden" name="payment_status" value="paid">
                        <button type="submit" class="btn-primary" style="background-color: #10B981;">Setujui</button>
                    </form>
                    <form action="{{ route('admin.orders.verifyPayment', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENOLAK pembayaran ini?');">
                        @csrf @method('PUT')
                        <input type="hidden" name="payment_status" value="rejected">
                        <button type="submit" class="btn-primary" style="background-color: #EF4444;">Tolak</button>
                    </form>
                </div>
                @else
                 <div class="bg-red-100 border border-red-200 text-red-800 text-center font-semibold p-4 rounded-lg">
                    Pembayaran {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@endsection