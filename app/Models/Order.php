<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'subtotal',
        'order_status',
        'payment_status',
        'address',
        'order_at',
        'send_at',
    ];

    protected $casts = [
        'order_at' => 'datetime',
        'send_at' => 'datetime',
        'subtotal' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

