<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'payment_status',
        'bukti_pembayaran',
        'paid_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getPaymentProofAttribute(): ?string
    {
        return $this->bukti_pembayaran;
    }
}
