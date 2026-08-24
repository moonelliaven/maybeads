<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}