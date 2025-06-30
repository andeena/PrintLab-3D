<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     * Ini akan mengubah kolom tertentu menjadi tipe data yang diinginkan secara otomatis.
     *
     * @var array
     */
    protected $casts = [
        'last_message_at' => 'datetime', // <-- TAMBAHKAN BLOK INI
    ];

    // ... (Relasi user, admin, dan messages Anda tetap di sini) ...

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

     public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}