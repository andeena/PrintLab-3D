<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class DesignController extends Controller
{
    /**
     * Menampilkan galeri semua desain global yang dibuat oleh Admin.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');

        // Query HANYA mengambil desain yang user_id-nya NULL (dibuat oleh admin).
        $designsQuery = Design::whereNull('user_id')->latest();

        if ($searchQuery) {
            $designsQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
            });
        }

        $designs = $designsQuery->paginate(8)->withQueryString();

        return view('dashboard.designs.index', compact('designs', 'searchQuery'));
    }

    /**
     * Menampilkan detail satu desain.
     */
    public function show(Design $design)
    {
        // Tambahkan pengecekan untuk memastikan user hanya bisa lihat desain global
        if ($design->user_id !== null) {
            // Jika desain ini ternyata milik user lain (bukan global), tolak akses.
            // Anda bisa sesuaikan logika ini jika user boleh melihat desain milik user lain yang publik.
            abort(404);
        }

        return view('dashboard.designs.show', compact('design'));
    }

    public function download(Design $design)
{
    // Otorisasi: pastikan user boleh men-download file ini (contoh sederhana)
    if (Auth::id() !== $design->user_id && !$design->is_public) {
        abort(403, 'Akses Ditolak');
    }

    // Ambil path file dari storage
    $filePath = Storage::disk('public')->path($design->file_path);

    // Jika file tidak ada, kembalikan error
    if (!Storage::disk('public')->exists($design->file_path)) {
        return back()->with('error', 'File desain tidak ditemukan.');
    }

    // Download file dengan nama aslinya
    return response()->download($filePath, $design->original_filename);
}
}