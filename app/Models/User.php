<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedIdAttribute(): string
    {
        return 'IDP' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Alamat user murni (opsional, jika belum diatur kembalikan null agar tidak tertukar dengan metode pengantaran)
     */
    public function getAddressAttribute($value): ?string
    {
        if (!empty($value) && !in_array(strtolower(trim($value)), ['ambil ditempat', 'ambil di tempat', '-', 'none', 'null'])) {
            return trim($value);
        }

        return null;
    }

    public function getPhoneAttribute($value): ?string
    {
        return !empty($value) ? $value : null;
    }

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->attributes['avatar'] ?? null)) {
            return asset('storage/avatars/' . $this->attributes['avatar']);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&size=128';
    }
}
