<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    protected $fillable = [
    'media_category_id',
    'title',
    'image',
];

public function category()
{
    return $this->belongsTo(MediaCategory::class, 'media_category_id');
}
}
