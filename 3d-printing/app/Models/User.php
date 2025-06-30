<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Import model-model yang direlasikan
use App\Models\Material; 
use App\Models\Design; 
use App\Models\Order;
use App\Models\Chat;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mendapatkan semua material yang dimiliki oleh user.
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * Mendapatkan semua desain yang dimiliki oleh user.
     * INI ADALAH METHOD BARU YANG PERLU ANDA TAMBAHKAN
     */
    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    /**
     * Mendapatkan semua pesanan yang dibuat oleh user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    // Seorang user bisa mengirim banyak pesan
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    /**
     * Mengecek apakah user memiliki role 'admin'.
     * Ini memerlukan kolom 'role' di tabel 'users' Anda.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}