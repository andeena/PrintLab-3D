<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MaterialController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Terapkan otorisasi menggunakan MaterialPolicy
        // $this->authorizeResource(Material::class, 'material');
        // Anda bisa aktifkan ini jika MaterialPolicy sudah siap untuk admin
    }

    // Menampilkan daftar semua material
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $materialsQuery = Material::with('user')->latest();

        if ($searchQuery) {
            $materialsQuery->where('name', 'LIKE', "%{$searchQuery}%");
        }

        $materials = $materialsQuery->paginate(10)->withQueryString();
        return view('admin.materials.index', compact('materials', 'searchQuery'));
    }

    // Menampilkan form untuk membuat material baru
    public function create()
    {
        return view('admin.materials.create');
    }

    // Menyimpan material baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'file' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'is_public' => 'sometimes|boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $safeName = Str::slug($originalName) . '_' . time() . '.' . $extension;
            $filePath = $request->file('file')->storeAs('materials/images', $safeName, 'public');
        }

        Material::create([
            'user_id' => null, // Dibuat oleh Admin
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'file_path' => $filePath,
            'file_size' => $request->file('file')->getSize(),
            'is_public' => $request->boolean('is_public'),
        ]);

        return redirect()->route('admin.materials.index')->with('success', 'Material baru berhasil ditambahkan.');
    }

    // Menampilkan detail satu material
    public function show(Material $material)
    {
        return view('admin.materials.show', compact('material'));
    }

    // Menampilkan form untuk mengedit material
    public function edit(Material $material)
    {
        return view('admin.materials.edit', compact('material'));
    }

    /**
     * Memperbarui material di database.
     * METHOD INI SEKARANG SUDAH DIISI LENGKAP
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120', // Gambar opsional saat update
            'is_public' => 'sometimes|boolean',
        ]);

        $dataToUpdate = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_public' => $request->boolean('is_public'),
        ];

        // Cek jika ada file gambar baru yang di-upload
        if ($request->hasFile('file')) {
            // Hapus file lama dari storage jika ada
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            // Simpan file baru
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $safeName = Str::slug($originalName) . '_' . time() . '.' . $extension;
            $newFilePath = $request->file('file')->storeAs('materials/images', $safeName, 'public');
            
            // Tambahkan path dan ukuran file baru ke data yang akan di-update
            $dataToUpdate['file_path'] = $newFilePath;
            $dataToUpdate['file_size'] = $request->file('file')->getSize();
        }

        // Lakukan update pada record material
        $material->update($dataToUpdate);

        return redirect()->route('admin.materials.index')->with('success', 'Material berhasil diperbarui.');
    }

    /**
     * Menghapus material.
     */
    public function destroy(Material $material)
    {
        // Hapus file gambar terkait dari storage
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        
        $material->delete();

        return redirect()->route('admin.materials.index')->with('success', 'Material berhasil dihapus.');
    }
}