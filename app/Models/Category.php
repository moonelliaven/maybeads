<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'category_name',
        'image',
    ];
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime',
    ];
}