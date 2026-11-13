<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Fillable
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
    ];
    // Hidden
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relasi dengan Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Accessor ID Terformat (contoh: IDP0098)
    public function getFormattedIdAttribute(): string
    {
        return 'IDP' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    // Accessor Alamat dengan fallback teratur
    public function getAddressAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        try {
            $latestOrder = $this->orders()->latest('id')->first();
            if ($latestOrder && !empty($latestOrder->address)) {
                return $latestOrder->address;
            }
        } catch (\Throwable $e) {
            // Ignore if orders table is not yet created
        }

        return 'Jl. 1 No. 5 Kampung Sigm..';
    }

    // Accessor Telepon dengan fallback
    public function getPhoneAttribute($value): string
    {
        return !empty($value) ? $value : '08' . str_pad((string)($this->id * 12345678 % 1000000000), 10, '8', STR_PAD_LEFT);
    }

    // Accessor Avatar
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->attributes['avatar'] ?? null)) {
            return asset('storage/avatars/' . $this->attributes['avatar']);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&size=128';
    }
}