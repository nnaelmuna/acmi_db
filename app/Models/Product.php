<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'images',
        'category',
        'title',
        'slug',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->title);
            }
        });

        static::updating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->title);
            }
        });
    }
}
