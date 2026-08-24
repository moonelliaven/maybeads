<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'category_name',
        'price',
        'quantity',
        'subtotal',
        'order_status',
        'payment_status',
        'address',
        'phone_number',
        'order_at',
        'send_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'order_at' => 'datetime',
        'send_at' => 'datetime',
    ];
}