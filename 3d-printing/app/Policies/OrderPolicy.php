<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Menentukan apakah pengguna boleh melihat daftar pesanan.
     * Logika untuk menampilkan HANYA pesanan milik mereka ada di controller.
     * Method ini hanya menentukan apakah mereka boleh mengakses halaman daftar pesanan secara umum.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user yang login boleh melihat halaman daftar pesanannya.
    }

    /**
     * Menentukan apakah pengguna boleh melihat detail pesanan tertentu.
     */
    public function view(User $user, Order $order): bool
    {
        // Pengguna hanya boleh melihat detail pesanan jika dia adalah pemiliknya.
        return $user->id === $order->user_id;
    }

    /**
     * Menentukan apakah pengguna boleh membuat pesanan baru.
     */
    public function create(User $user): bool
    {
        // Semua user yang login boleh membuka form pembuatan pesanan.
        return true;
    }

    /**
     * Menentukan apakah pengguna boleh memperbarui pesanan.
     */
    public function update(User $user, Order $order): bool
    {
        // Pengguna hanya boleh update pesanan jika dia pemilik DAN status pesanan masih 'pending'.
        return $user->id === $order->user_id && $order->status === 'pending';
    }

    /**
     * Menentukan apakah pengguna boleh menghapus atau membatalkan pesanan.
     */
    public function delete(User $user, Order $order): bool
    {
        // Pengguna hanya boleh hapus/batal pesanan jika dia pemilik DAN status pesanan masih 'pending'.
        return $user->id === $order->user_id && $order->status === 'pending';
    }

    /**
     * Determine whether the user can restore the model.
     * (Abaikan jika tidak menggunakan Soft Deletes)
     */
    // public function restore(User $user, Order $order): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     * (Abaikan jika tidak menggunakan Soft Deletes)
     */
    // public function forceDelete(User $user, Order $order): bool
    // {
    //     //
    // }
}