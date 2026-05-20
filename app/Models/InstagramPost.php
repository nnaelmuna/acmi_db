<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'apify_id',
        'caption',
        'post_url',
        'apify_image_url',
        'local_image_path',
        'posted_at',
    ];
}
