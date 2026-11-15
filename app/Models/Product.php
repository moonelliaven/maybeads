<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    public $timestamps = false;

    protected $appends = ['category_name'];

    public function getCategoryNameAttribute(): string
    {
        return $this->category?->category_name ?? 'Tanpa Kategori';
    }

    protected $fillable = [
        'category_id',
        'secondary_categories',
        'product_name',
        'image',
        'secondary_images',
        'description',
        'price',
        'stock',
        'order_count',
        'status',
    ];

    protected $casts = [
        'created_at'           => 'datetime',
        'secondary_categories' => 'array',
        'secondary_images'     => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'product_id');
    }

    public function getAvgRatingAttribute(): float
    {
        try {
            if ($this->relationLoaded('testimonials')) {
                $avg = $this->testimonials->avg('rating');
            } else {
                $avg = $this->testimonials()->avg('rating');
            }
            return $avg ? round((float)$avg, 1) : 5.0;
        } catch (\Throwable $e) {
            return 5.0;
        }
    }

    public function getReviewCountAttribute(): int
    {
        try {
            if ($this->relationLoaded('testimonials')) {
                return $this->testimonials->count();
            }
            return $this->testimonials()->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
