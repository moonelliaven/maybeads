<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'variants',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'created_at'           => 'datetime',
        'secondary_categories' => 'array',
        'variants'             => 'array',
        'secondary_images'     => 'array',
        'status'               => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
