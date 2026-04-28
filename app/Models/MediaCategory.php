<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class);
    }

}
