<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'account_number',
        'account_name',
        'price',
        'status',
        'description',
        'icon',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getTypeLabelAttribute(): string
    {
        return match (strtolower($this->type)) {
            'bank'      => 'Transfer Bank',
            'auto-link' => 'Auto-Link',
            'qris'      => 'QRIS',
            'e-wallet'  => 'E-Wallet',
            default     => ucfirst($this->type ?? 'E-Wallet'),
        };
    }

    public function getTotalAccumulatedAttribute(): float
    {
        $sumByFk = $this->payments()
            ->whereIn('payment_status', ['Sukses', 'sukses', 'paid', 'Success'])
            ->sum('amount');

        if ($sumByFk > 0) {
            return (float)$sumByFk;
        }

        // Fallback matching by name / code in payments table
        return (float)Payment::where(function ($q) {
            $q->where('payment_method_id', $this->id)
              ->orWhere('payment_method', 'like', '%' . $this->name . '%')
              ->orWhere('payment_method', 'like', '%' . $this->code . '%');
        })
        ->whereIn('payment_status', ['Sukses', 'sukses', 'paid', 'Success'])
        ->sum('amount');
    }

    public function getTransactionsCountAttribute(): int
    {
        $countByFk = $this->payments()
            ->whereIn('payment_status', ['Sukses', 'sukses', 'paid', 'Success'])
            ->count();

        if ($countByFk > 0) {
            return $countByFk;
        }

        return Payment::where(function ($q) {
            $q->where('payment_method_id', $this->id)
              ->orWhere('payment_method', 'like', '%' . $this->name . '%')
              ->orWhere('payment_method', 'like', '%' . $this->code . '%');
        })
        ->whereIn('payment_status', ['Sukses', 'sukses', 'paid', 'Success'])
        ->count();
    }
}

