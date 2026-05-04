<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahin ini

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Relasi ke model Product
     * Ini yang dicari sama fungsi withCount('products')
     */
    public function products(): HasMany
    {
        // Sesuaikan 'category' dengan nama kolom kategori di tabel products kamu
        // Kalau di tabel products nama kolomnya 'category_id', ganti 'category' jadi 'category_id'
        return $this->hasMany(Product::class, 'category', 'name');
    }
}