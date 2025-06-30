@extends('dashboard')

@section('title', 'Manajemen Material')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold">Manajemen Material</h1>
    <a href="{{ route('admin.materials.create') }}" class="btn-primary flex items-center whitespace-nowrap">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Material Baru
    </a>
</div>

{{-- FORM PENCARIAN --}}
<form method="GET" action="{{ route('admin.materials.index') }}" class="mb-6">
    <div class="flex flex-col sm:flex-row gap-2">
        <input type="text" name="search" placeholder="Cari nama atau deskripsi material..."
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
               value="{{ $searchQuery ?? '' }}">
        <div class="flex space-x-2">
            <button type="submit" class="btn-primary px-6 whitespace-nowrap">
                <svg class="w-5 h-5 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                Cari
            </button>
            @if(isset($searchQuery) && $searchQuery)
                <a href="{{ route('admin.materials.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 whitespace-nowrap flex items-center shadow-sm">
                   Reset
                </a>
            @endif
        </div>
    </div>
</form>

@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($materials as $material)
                <tr class="hover:bg-orange-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-md object-cover" src="{{ $material->thumbnail_url }}" alt="{{ $material->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 truncate" style="max-width: 250px;" title="{{ $material->name }}">
                                    {{ $material->name }}
                                </div>
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($material->description, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{-- Tampilkan nama user jika ada, atau "Admin" jika user_id nya NULL --}}
                        {{ $material->user->name ?? 'Global (Admin)' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right">
                        Rp {{ number_format($material->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $material->is_public ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $material->is_public ? 'Publik' : 'Privat' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                        <div class="flex space-x-2 justify-center">
                            <a href="{{ route('admin.materials.show', $material->id) }}" class="text-gray-500 hover:text-orange-500" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.materials.edit', $material->id) }}" class="text-gray-500 hover:text-blue-500" title="Edit Material">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus material \'{{ addslashes($material->name) }}\'?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-500" title="Hapus Material">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                        Tidak ada material yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($materials->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $materials->links() }}
    </div>
    @endif
</div>
@endsection