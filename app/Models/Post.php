<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'status',

        'title_en',
        'description_en',
        'content_en',

        'title_id',
        'description_id',
        'content_id',
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
