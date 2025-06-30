<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Mengambil data untuk kartu statistik
        $stats = [
            'total_materials' => $user->materials()->count(),
            'active_orders'   => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
            'total_spending'  => $user->orders()->where('status', 'completed')->sum('total_price'),
        ];

        // 2. Mengambil data untuk "Katalog Material" (menampilkan material publik)
        $latestMaterials = Material::where('is_public', true)
                                   ->latest()
                                   ->take(3)
                                   ->get();

        // 3. Mengambil data untuk "Pesanan Terbaru"
        // FIX: Eager loading diubah untuk memuat relasi yang benar
        // Memuat 'items' dari setiap pesanan, dan di dalam setiap item, muat data 'material'-nya
        $latestOrders = $user->orders()
                             ->with('items.material')
                             ->latest()
                             ->take(5)
                             ->get();
        
        // 4. Mengirim semua data yang sudah diambil ke view 'dashboard.index'
        // Pastikan nama view Anda adalah 'dashboard/index.blade.php'
        return view('dashboard.index', compact('stats', 'latestMaterials', 'latestOrders'));
    }
}