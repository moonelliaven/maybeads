<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
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

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'order_at' => 'datetime',
        'send_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}