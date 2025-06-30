<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    // Hapus field item dari sini
    protected $fillable = [
        'user_id', 'notes', 'recipient_name', 'shipping_address', 'phone_number',
        'status', 'total_price', 'payment_method', 'payment_proof', 'payment_status'
    ];

    // Tambahkan relasi hasMany ke OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke User (kemungkinan sudah ada)
    public function user() { return $this->belongsTo(User::class); }
}