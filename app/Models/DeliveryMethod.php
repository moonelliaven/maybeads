<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'price',
        'status',
        'description',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'delivery_method_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}

