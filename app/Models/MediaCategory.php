<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_default'
    ];

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class);
    }

}
