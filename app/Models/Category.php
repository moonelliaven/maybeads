<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->products()->sum('stock');
    }
}
