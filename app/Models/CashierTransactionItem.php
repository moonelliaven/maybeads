<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierTransactionItem extends Model
{
    protected $table = 'cashier_transaction_items';

    protected $fillable = [
        'cashier_transaction_id',
        'product_id',
        'product_name',
        'variant_info',
        'price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price'      => 'float',
        'quantity'   => 'integer',
        'subtotal'   => 'float',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CashierTransaction::class, 'cashier_transaction_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

