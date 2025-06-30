@extends('dashboard')

@section('title', 'Buat Pesanan Baru')

@push('styles')
<style>
    .order-item-block { transition: all 0.3s ease-in-out; }
</style>
@endpush

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pesanan Baru</h1>
    <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-orange-500">&larr; Kembali ke Daftar Pesanan</a>
</div>

@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow" role="alert">
        <p class="font-bold">Terjadi Error di Server!</p>
        <p class="text-sm mt-1">{{ session('error') }}</p>
    </div>
@endif

{{-- Ini untuk menampilkan error validasi (jika ada) --}}
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow" role="alert">
        <p class="font-bold">Oops! Ada beberapa kesalahan validasi:</p>
        <ul class="list-disc list-inside mt-2 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow" role="alert">
        <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
        <ul class="list-disc list-inside mt-2 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data" class="card p-6 md:p-8">
    @csrf

    {{-- BAGIAN 1: ITEM-ITEM PESANAN --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold border-b pb-2 mb-4 text-gray-800">1. Item Pesanan</h2>
        <div id="order-items-container" class="space-y-6">
            {{-- Item akan dimuat di sini oleh JavaScript --}}
        </div>
        <button type="button" id="add-item-btn" class="mt-4 text-sm font-medium text-orange-600 hover:text-orange-800 flex items-center transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Tambah Item Lain
        </button>
    </div>

    {{-- BAGIAN 2: DETAIL PENGIRIMAN --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold border-b pb-2 mb-4 text-gray-800">2. Detail Pengiriman & Catatan Umum</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div>
                <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima <span class="text-red-500">*</span></label>
                <input type="text" name="recipient_name" id="recipient_name" value="{{ old('recipient_name', Auth::user()->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: 08123456789">
            </div>
            <div class="md:col-span-2">
                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                <textarea name="shipping_address" id="shipping_address" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('shipping_address') }}</textarea>
            </div>
             <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan untuk Keseluruhan Pesanan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>
    
    {{-- BAGIAN 3: TOTAL HARGA & PEMBAYARAN --}}
    <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-200">
        <div class="bg-orange-50 rounded-lg p-4 flex justify-between items-center mb-8">
            <span class="text-lg font-semibold text-gray-700">Estimasi Total Harga:</span>
            <span id="total-price-display" class="text-2xl font-bold text-orange-600">Rp 0</span>
        </div>
        <h2 class="text-lg font-semibold border-b pb-2 mb-4 text-gray-800">3. Detail Pembayaran</h2>
        <div class="space-y-6">
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                <select id="payment_method" name="payment_method" required class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>-- Pilih Metode --</option>
                    <option value="mandiri" {{ old('payment_method') == 'mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                    <option value="gopay" {{ old('payment_method') == 'gopay' ? 'selected' : '' }}>Gopay</option>
                    <option value="ovo" {{ old('payment_method') == 'ovo' ? 'selected' : '' }}>OVO</option>
                    <option value="dana" {{ old('payment_method') == 'dana' ? 'selected' : '' }}>DANA</option>
                    <option value="shopeepay" {{ old('payment_method') == 'shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                </select>
            </div>
            <div id="payment-info" class="p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 text-sm rounded-r-lg">
                <p>Silakan lakukan pembayaran sesuai <strong>Estimasi Total Harga</strong> di atas, lalu unggah bukti pembayaran Anda di bawah ini.</p>
                <p class="mt-2 font-semibold">Bank Mandiri: 123-456-7890 a.n. PrintLab 3D</p>
                <p class="font-semibold">E-Wallet (Gopay/OVO/DANA/ShopeePay): 08123456789 a.n. PrintLab 3D</p>
            </div>
            <div>
                <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                <input type="file" id="payment_proof" name="payment_proof" required accept="image/jpeg,image/png,jpg,webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                <img id="image-preview" src="#" alt="Image Preview" class="mt-4 rounded-lg shadow-sm" style="display: none; max-height: 250px;">
            </div>
        </div>
    </div>
    
    <div class="mt-8 pt-5 border-t border-gray-200">
        <div class="flex justify-end">
            <a href="{{ route('orders.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</a>
            <button type="submit" class="ml-3 btn-primary">Buat Pesanan</button>
        </div>
    </div>
</form>

{{-- ======================================================= --}}
{{-- TEMPLATE UNTUK ITEM BARU (SUDAH DIPERBAIKI SECARA TOTAL) --}}
{{-- ======================================================= --}}
<div id="order-item-template" style="display: none;">
    <div class="order-item-block border p-4 rounded-lg relative space-y-4 bg-slate-50">
        <button type="button" class="remove-item-btn absolute -top-3 -right-3 bg-red-500 text-white rounded-full h-7 w-7 flex items-center justify-center text-lg font-bold hover:bg-red-600 transition-transform hover:scale-110" title="Hapus Item">&times;</button>
        
        {{-- FIX: Menambahkan `name` yang benar ke input Desain --}}
        <div>
            <label for="design_id___INDEX__" class="block text-sm font-medium text-gray-700 mb-1">Desain (Opsional)</label>
            <select name="items[__INDEX__][design_id]" id="design_id___INDEX__" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                <option value="">-- Cetak Kustom (Tanpa Desain) --</option>
                @foreach($userDesigns as $design)
                    <option value="{{ $design->id }}">{{ $design->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="material_id___INDEX__" class="block text-sm font-medium text-gray-700 mb-1">Material<span class="text-red-500">*</span></label>
                <select name="items[__INDEX__][material_id]" id="material_id___INDEX__" required class="order-item-material w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="" data-price="0">-- Pilih Material --</option>
                    @foreach($userMaterials as $material)
                        <option value="{{ $material->id }}" data-price="{{ $material->price }}">{{ $material->name }} - Rp {{ number_format($material->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
             <div>
                <label for="color___INDEX__" class="block text-sm font-medium text-gray-700 mb-1">Warna <span class="text-red-500">*</span></label>
                <select name="items[__INDEX__][color]" id="color___INDEX__" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Pilih Warna</option>
                    @foreach($colors as $color)
                        <option value="{{ $color }}">{{ $color }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label for="quantity___INDEX__" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
            <input type="number" name="items[__INDEX__][quantity]" id="quantity___INDEX__" value="1" min="1" required class="order-item-quantity w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
        </div>
        
        <div>
            <label for="item_notes___INDEX__" class="block text-sm font-medium text-gray-700 mb-1">Catatan untuk Item ini (Opsional)</label>
            <textarea name="items[__INDEX__][notes]" id="item_notes___INDEX__" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: Cetak dengan ketebalan 0.2mm"></textarea>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('order-items-container');
    const addButton = document.getElementById('add-item-btn');
    const template = document.getElementById('order-item-template').innerHTML;
    const priceDisplay = document.getElementById('total-price-display');
    let itemIndex = 0;

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item-block').forEach(itemBlock => {
            const materialSelect = itemBlock.querySelector('.order-item-material');
            const quantityInput = itemBlock.querySelector('.order-item-quantity');
            
            if (materialSelect && quantityInput) {
                const selectedOption = materialSelect.options[materialSelect.selectedIndex];
                const price = parseFloat(selectedOption.dataset.price) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                total += price * quantity;
            }
        });
        if (priceDisplay) {
            priceDisplay.textContent = formatRupiah(total);
        }
    }

    function addItem() {
        const newItemHtml = template.replace(/__INDEX__/g, itemIndex);
        const newItemDiv = document.createElement('div');
        newItemDiv.innerHTML = newItemHtml;
        
        const newItemBlock = newItemDiv.firstElementChild;

        newItemBlock.querySelector('.remove-item-btn').addEventListener('click', function() {
            this.closest('.order-item-block').remove();
            calculateTotal();
        });

        newItemBlock.querySelector('.order-item-material').addEventListener('change', calculateTotal);
        newItemBlock.querySelector('.order-item-quantity').addEventListener('input', calculateTotal);

        container.appendChild(newItemBlock);
        itemIndex++;
        calculateTotal();
    }

    addButton.addEventListener('click', addItem);
    if (container.children.length === 0) {
        addItem();
    }

    const paymentProofInput = document.getElementById('payment_proof');
    const imagePreview = document.getElementById('image-preview');

    if (paymentProofInput) {
        paymentProofInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
