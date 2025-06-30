<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Material;
use App\Models\Design;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard admin beserta statistik.
     */
    public function index()
    {
        // Ambil data statistik dari seluruh aplikasi
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_materials' => Material::count(),
            'total_designs' => Design::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_price'), // Contoh pendapatan
        ];

        // Ambil beberapa pesanan terbaru untuk ditampilkan
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentOrders'));
    }
}