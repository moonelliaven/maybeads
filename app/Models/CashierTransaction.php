<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashierTransaction extends Model
{
    protected $table = 'cashier_transactions';

    protected $fillable = [
        'transaction_code',
        'cashier_id',
        'customer_name',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'cash_paid',
        'change_amount',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'subtotal'        => 'float',
        'discount_amount' => 'float',
        'tax_amount'      => 'float',
        'total_amount'    => 'float',
        'cash_paid'       => 'float',
        'change_amount'   => 'float',
        'created_at'      => 'datetime',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CashierTransactionItem::class, 'cashier_transaction_id');
    }
}

