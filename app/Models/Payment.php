<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'delivery_method_id',
        'payment_method',
        'amount',
        'payment_status',
        'bukti_pembayaran',
        'paid_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function getPaymentProofAttribute(): ?string
    {
        return $this->bukti_pembayaran;
    }

    public function getFormattedIdAttribute(): string
    {
        return 'IDP' . str_pad((string)$this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedDateAttribute(): string
    {
        $dt = $this->paid_at ?? $this->created_at ?? $this->order?->order_at ?? now();
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
            9 => 'Sept', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];
        $mStr = $months[$dt->month] ?? $dt->format('M');
        return $mStr . ' ' . $dt->format('d Y H.i');
    }
}
