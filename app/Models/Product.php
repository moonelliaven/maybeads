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
        'secondary_categories',
        'product_name',
        'image',
        'secondary_images',
        'description',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'created_at'           => 'datetime',
        'secondary_categories' => 'array',
        'secondary_images'     => 'array',
        'status'               => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function getVariantsAttribute()
    {
        if (! $this->relationLoaded('variants')) {
            $this->setRelation('variants', $this->variants()->get());
        }

        return $this->getRelation('variants');
    }
}
