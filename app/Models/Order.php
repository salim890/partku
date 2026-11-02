<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'order_number',      // <-- INI YANG TADI HILANG
        'customer_name', 
        'customer_email',    // <-- INI JUGA
        'customer_phone', 
        'customer_address', 
        'payment_method', 
        'total_amount', 
        'status',
        'shipping_method',   // <-- INI JUGA
        'shipping_cost',     // <-- INI JUGA
        'tracking_number'
    ];

    // Relasi: Satu Order punya banyak OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}