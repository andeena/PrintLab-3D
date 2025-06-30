<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Menampilkan daftar semua material global yang dibuat oleh Admin.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');

        // Query HANYA mengambil material yang user_id-nya NULL (dibuat oleh admin).
        $materialsQuery = Material::whereNull('user_id')->latest();

        if ($searchQuery) {
            $materialsQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
            });
        }

        $materials = $materialsQuery->paginate(9)->withQueryString();

        return view('dashboard.materials.index', compact('materials', 'searchQuery'));
    }

    /**
     * Menampilkan detail satu material.
     */
    public function show(Material $material)
    {
        // Kita bisa tambahkan pengecekan di sini untuk memastikan user hanya bisa
        // melihat material yang global atau miliknya (jika ada sisa data lama)
        // if ($material->user_id !== null && $material->user_id !== auth()->id()) {
        //     abort(403);
        // }

        return view('dashboard.materials.show', compact('material'));
    }
}