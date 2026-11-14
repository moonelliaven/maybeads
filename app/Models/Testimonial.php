<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'message',
        'rating',
        'is_store_testimonial',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'rating'               => 'integer',
        'is_store_testimonial' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
