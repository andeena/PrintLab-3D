<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Pastikan ini di-import

class DesignController extends Controller
{
    use AuthorizesRequests; // Pastikan controller menggunakan trait ini

    /**
     * Terapkan authorization untuk semua method resource secara otomatis.
     */
    public function __construct()
    {
        // Ini akan memanggil method yang sesuai di DesignPolicy untuk setiap aksi.
        // Contoh: create() akan cek 'create', edit() akan cek 'update', dll.
        $this->authorizeResource(Design::class, 'design');
    }

    /**
     * Menampilkan daftar semua desain (global dan milik user).
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        
        $designsQuery = Design::with('user')->latest();

        if ($searchQuery) {
            $designsQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('description', 'LIKE', "%{$searchQuery}%");
            });
        }

        $designs = $designsQuery->paginate(8)->withQueryString();

        return view('admin.designs.index', compact('designs', 'searchQuery'));
    }

    /**
     * Menampilkan form untuk membuat desain global baru.
     */
    public function create()
    {
        return view('admin.designs.create');
    }

    /**
     * Menyimpan desain global baru yang dibuat oleh admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'design_file' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('design_file') && $request->file('design_file')->isValid()) {
            $originalName = pathinfo($request->file('design_file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('design_file')->getClientOriginalExtension();
            $safeName = Str::slug($originalName) . '_' . time() . '.' . $extension;
            // Simpan ke folder yang sesuai, misal 'designs/admin_uploads'
            $filePath = $request->file('design_file')->storeAs('designs/admin_uploads', $safeName, 'public');
        }
        
        Design::create([
            'user_id' => null, // user_id NULL untuk menandakan ini desain global/admin
            'name' => $validated['name'],
            'description' => $validated['description'],
            'file_path' => $filePath,
        ]);

        return redirect()->route('admin.designs.index')->with('success', 'Desain global baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu desain.
     */
    public function show(Design $design)
    {
        return view('admin.designs.show', compact('design'));
    }

    /**
     * Menampilkan form untuk mengedit desain.
     */
    public function edit(Design $design)
    {
        return view('admin.designs.edit', compact('design'));
    }

    /**
     * Memperbarui data desain di database.
     */
    public function update(Request $request, Design $design)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'design_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // Opsional saat update
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'description' => $validated['description'],
        ];

        // Cek jika ada file gambar baru yang di-upload untuk menggantikan yang lama
        if ($request->hasFile('design_file')) {
            // Hapus file lama jika ada
            if ($design->file_path && Storage::disk('public')->exists($design->file_path)) {
                Storage::disk('public')->delete($design->file_path);
            }
            // Simpan file baru
            $originalName = pathinfo($request->file('design_file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('design_file')->getClientOriginalExtension();
            $safeName = Str::slug($originalName) . '_' . time() . '.' . $extension;
            $newFilePath = $request->file('design_file')->storeAs('designs/admin_uploads', $safeName, 'public');
            $dataToUpdate['file_path'] = $newFilePath;
        }

        $design->update($dataToUpdate);

        return redirect()->route('admin.designs.index')->with('success', 'Desain berhasil diperbarui!');
    }

    /**
     * Menghapus desain dari database.
     */
    public function destroy(Design $design)
    {
        // Hapus file gambar terkait dari storage
        if ($design->file_path && Storage::disk('public')->exists($design->file_path)) {
            Storage::disk('public')->delete($design->file_path);
        }
        
        // Hapus record dari database
        $design->delete();

        return redirect()->route('admin.designs.index')->with('success', 'Desain berhasil dihapus!');
    }
}