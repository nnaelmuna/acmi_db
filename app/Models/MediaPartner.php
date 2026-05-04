<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaPartner extends Model
{
    protected $fillable = [
        'name',
        'image',
        'link',
        'start_date',
        'end_date',
    ];
}
