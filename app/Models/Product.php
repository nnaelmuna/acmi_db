<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'image', 
        'images',     
        'category', 
        'title', 
        'company_name', 
        'ceo_name', 
        'description', 
        'features', 
        'website', 
        'email', 
        'phone', 
        'status'
    ];

    protected $casts = [
        'images'   => 'array', // <--- Harus sama dengan yang di atas
        'image' => 'array',
        'features' => 'array',
        'category' => 'array',
    ];
}