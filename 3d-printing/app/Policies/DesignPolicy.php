<?php

namespace App\Policies;

use App\Models\Design;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DesignPolicy
{
    /**
     * Lakukan pengecekan pra-otorisasi.
     * Method ini akan berjalan SEBELUM method lainnya (view, update, dll).
     */
    public function before(User $user, string $ability): bool|null
    {
        // Jika pengguna memiliki method isAdmin() dan hasilnya true...
        if ($user->isAdmin()) {
            // ...maka berikan akses penuh untuk melakukan aksi apapun.
            return true;
        }

        // Jika bukan admin, kembalikan null agar Laravel melanjutkan
        // ke pengecekan method spesifik di bawah (seperti update, delete, dll).
        return null;
    }

    /**
     * Menentukan apakah pengguna boleh melihat daftar (halaman index) dari resource.
     */
    public function viewAny(User $user): bool
    {
        // Izinkan semua pengguna yang telah login untuk melihat halaman daftar "Desain Saya".
        // Controller yang akan memfilter isinya.
        return true;
    }

    /**
     * Menentukan apakah pengguna boleh melihat detail dari satu desain.
     */
    public function view(User $user, Design $design): bool
    {
        // Hanya izinkan jika pengguna adalah pemilik dari desain tersebut.
        // Admin akan lolos dari aturan ini karena method before().
        return $user->id === $design->user_id;
    }

    /**
     * Menentukan apakah pengguna boleh membuat desain baru.
     */
    public function create(User $user): bool
    {
        // Semua pengguna yang login bisa mencoba membuka form untuk membuat desain.
        return true;
    }

    /**
     * Menentukan apakah pengguna boleh memperbarui desain yang ada.
     */
    public function update(User $user, Design $design): bool
    {
        // Hanya izinkan jika pengguna adalah pemilik dari desain tersebut.
        // Admin akan lolos dari aturan ini karena method before().
        return $user->id === $design->user_id;
    }

    /**
     * Menentukan apakah pengguna boleh menghapus desain.
     */
    public function delete(User $user, Design $design): bool
    {
        // Hanya izinkan jika pengguna adalah pemilik dari desain tersebut.
        // Admin akan lolos dari aturan ini karena method before().
        return $user->id === $design->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Design $design): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Design $design): bool
    // {
    //     //
    // }
}