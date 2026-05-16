<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
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
        'status',
        'address'
    ];

    protected $casts = [
        'images'   => 'array', // <--- Harus sama dengan yang di atas
        'image' => 'array',
        'features' => 'array',
        'category' => 'array',
    ];
}