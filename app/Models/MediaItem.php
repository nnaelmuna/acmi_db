<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaItem extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
    'media_category_id',
    'title',
    'image',
    'status',
];

public function category()
{
    return $this->belongsTo(MediaCategory::class, 'media_category_id');
}
}
