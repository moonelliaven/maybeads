<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'photo_profile',
        'phone_number',
        'address',
        'role',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'user_id');
    }
}