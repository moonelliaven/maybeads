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
        if ($this->payment && $this->payment->deliveryMethod) {
            return (float)$this->payment->deliveryMethod->price;
        }
        return $this->delivery_type === 'Ambil Ditempat' ? 200.00 : 15000.00;
    }

    public function getAdminFeeAttribute(): float
    {
        if ($this->payment && $this->payment->paymentMethod) {
            return (float)$this->payment->paymentMethod->price;
        }
        return 500.00;
    }

    public function getGrandTotalAttribute(): float
    {
        return (float)$this->subtotal + $this->delivery_fee + $this->admin_fee;
    }

    public function getPaymentIdAttribute(): ?string
    {
        return $this->payment?->formatted_id ?? ('IDP' . str_pad((string)$this->id, 4, '0', STR_PAD_LEFT));
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return $this->payment?->payment_method
            ?? $this->payment?->paymentMethod?->name
            ?? 'QRIS';
    }

    public function getDeliveryMethodNameAttribute(): string
    {
        return $this->payment?->deliveryMethod?->name
            ?? $this->delivery_type;
    }

    public function getIsDeliveryToHomeAttribute(): bool
    {
        $type = strtolower($this->delivery_method_name);
        return str_contains($type, 'antar') || str_contains($type, 'rumah') || (str_contains($type, 'tempat') && !str_contains($type, 'ambil'));
    }

    public function getDeadlineAtAttribute(): ?\Illuminate\Support\Carbon
    {
        return $this->order_at ? $this->order_at->copy()->addDays(5) : null;
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->deadline_at) return false;
        // Expired jika lebih dari 5 hari dan belum selesai/sukses
        $isFinished = in_array(strtolower($this->order_status), ['selesai', 'sukses']);
        return !$isFinished && now()->greaterThan($this->deadline_at);
    }

    public function getCanCancelByAdminAttribute(): bool
    {
        $statusLower = strtolower($this->order_status);
        $isFinished = in_array($statusLower, ['selesai', 'sukses', 'dibatalkan']);
        $hasPayment = $this->payment !== null || !empty($this->payment_status);
        // Bisa dibatalkan admin jika ada pembayaran, belum selesai/batal, dan masih dalam jangka waktu 5 hari
        return $hasPayment && !$isFinished && !$this->is_expired;
    }

    public function getRemainingTimeTextAttribute(): string
    {
        if (!$this->deadline_at) return '-';
        if ($this->is_expired || strtolower($this->order_status) === 'dibatalkan') {
            return 'Waktu Habis (Otomatis Dibatalkan)';
        }
        if (in_array(strtolower($this->order_status), ['selesai', 'sukses'])) {
            return 'Pesanan Telah Selesai';
        }

        $diffHours = now()->diffInHours($this->deadline_at, false);
        if ($diffHours <= 0) {
            return 'Waktu Habis';
        }

        $days = floor($diffHours / 24);
        $hours = $diffHours % 24;

        if ($days > 0) {
            return "Sisa {$days} Hari {$hours} Jam";
        }
        return "Sisa {$hours} Jam";
    }
}
