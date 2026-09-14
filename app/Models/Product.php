<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'product_name',
        'image',
        'description',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }
}