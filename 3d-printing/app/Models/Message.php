<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    // Satu pesan dimiliki oleh satu pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Satu pesan berada dalam satu percakapan
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}