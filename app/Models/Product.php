<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'image', 'secondary_images', 'category', 'title', 
        'company_name', 'ceo_name', 'description', 'features', 'website', 'email', 'phone', 'status'
    ];

    // INI PENTING: Biar array bisa masuk ke database
    protected $casts = [
        'images' => 'array',
        'features' => 'array',
    ];
}