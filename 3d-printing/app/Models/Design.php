<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'file_path',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Setiap desain dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk mendapatkan URL lengkap dari file desain.
     * Ini memungkinkan Anda memanggil $design->design_file_url di view.
     *
     * @return string
     */
    public function getDesignFileUrlAttribute(): string
    {
        // Cek apakah path file ada dan file fisiknya ada di storage
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            // Jika ada, kembalikan URL publiknya
            return Storage::disk('public')->url($this->file_path);
        }

        // Jika tidak, kembalikan URL ke gambar placeholder default
        // Pastikan Anda memiliki gambar ini di public/images/placeholder_design.png
        return asset('images/placeholder_design.png');
    }
}