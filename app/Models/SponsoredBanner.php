<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsoredBanner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'size',
        'link_sponsored',
        'start_date',
        'end_date',
        'is_forever',
        'impressions',
        'status',
    ];

    protected $casts = [
        'is_forever' => 'boolean',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'impressions' => 'integer',
    ];
}
