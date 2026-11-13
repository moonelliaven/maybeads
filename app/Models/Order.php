<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

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
        'subtotal' => 'decimal:2',
        'order_at' => 'datetime',
        'send_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function getBuktiPembayaranAttribute(): ?string
    {
        return $this->payment?->bukti_pembayaran;
    }

    public function getFormattedIdAttribute(): string
    {
        return str_pad((string)$this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getOrderCodeAttribute(): string
    {
        return '#MBDS-' . str_pad((string)$this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getDeliveryTypeAttribute(): string
    {
        if (empty($this->address)) {
            return 'Ambil Ditempat';
        }
        $addr = strtolower($this->address);
        if (str_contains($addr, 'ambil') || str_contains($addr, 'ditempat') || str_contains($addr, 'di tempat')) {
            return 'Ambil Ditempat';
        }
        return 'Antar ketempat';
    }

    public function getDeliveryFeeAttribute(): float
    {
        return $this->delivery_type === 'Ambil Ditempat' ? 0.00 : 15000.00;
    }

    public function getAdminFeeAttribute(): float
    {
        return 2000.00;
    }

    public function getGrandTotalAttribute(): float
    {
        return (float)$this->subtotal + $this->delivery_fee + $this->admin_fee;
    }
}
