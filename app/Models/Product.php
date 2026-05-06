<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'image', 'secondary_images', 'category', 'title', 
        'company_name', 'ceo_name', 'description', 'features', 'website', 'email', 'phone', 'status'
    ];

    // INI PENTING: Biar array bisa masuk ke database
    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'category' => 'array',
    ];
}