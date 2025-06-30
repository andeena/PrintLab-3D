<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'design_id', 
        'design_name',
        'material_id',
        'material_name',
        'color',
        'quantity',
        'price',
        'subtotal',
        'notes',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function material() { return $this->belongsTo(Material::class); }

    public function design() { return $this->belongsTo(Design::class); }
}