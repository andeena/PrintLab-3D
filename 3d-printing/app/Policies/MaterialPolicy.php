<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialPolicy
{
    /**
     * Determine whether the user can view the model.
     * Pengguna bisa melihat detail material jika material itu publik ATAU itu miliknya.
     */
    public function view(User $user, Material $material): bool
    {
        return $material->is_public || $user->id === $material->user_id;
    }

    /**
     * Determine whether the user can update the model.
     * Pengguna hanya bisa update jika dia adalah pemilik material tersebut.
     */
    public function update(User $user, Material $material): bool
    {
        return $user->id === $material->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Pengguna hanya bisa hapus jika dia adalah pemilik material tersebut.
     */
    public function delete(User $user, Material $material): bool
    {
        return $user->id === $material->user_id;
    }

    // Method lain seperti viewAny dan create bisa Anda biarkan return true
    // jika semua user yang login boleh melihat daftar dan membuat material.
}