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

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return asset('images/products/sample-hub.jpg');
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        $cleaned = ltrim($this->image, '/');
        if (file_exists(public_path('storage/' . $cleaned))) {
            return asset('storage/' . $cleaned);
        }
        if (file_exists(public_path($cleaned))) {
            return asset($cleaned);
        }
        return asset('storage/' . $cleaned);
    }
}
