<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan untuk admin.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'string', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
        ]);

        $status = $request->query('status');
        
        // FIX: Eager loading diubah dari 'design' dan 'materialDetail' menjadi relasi yang benar
        // Memuat relasi 'user' (pemesan) dan 'items' untuk setiap pesanan
        $ordersQuery = Order::with(['user', 'items'])->latest();

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->paginate(15)->withQueryString(); // Tampilkan 15 per halaman untuk admin

        // Menghitung jumlah pesanan per status untuk tab filter
        $statusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Menampilkan detail dari satu pesanan spesifik.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.design', 'items.material']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mengupdate status pesanan.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
        ]);

        $order->status = $validated['status'];

        if ($order->status == 'processing' && $order->payment_status != 'paid') {
            $order->payment_status = 'paid';
        }
        if ($order->status == 'cancelled') {
            $order->payment_status = 'cancelled';
        }

        $order->save();

        return back()->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui.');
    }

    /**
     * Memverifikasi pembayaran sebuah pesanan.
     */
    public function verifyPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'string', Rule::in(['paid', 'rejected'])],
        ]);

        $order->payment_status = $validated['payment_status'];

        if ($order->payment_status === 'paid') {
            $order->status = 'processing';
        }

        $order->save();

        return back()->with('success', 'Status pembayaran untuk pesanan #' . $order->id . ' telah diperbarui.');
    }
}
