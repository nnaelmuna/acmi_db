<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaPartner extends Model
{
    use HasFactory, SoftDeletes;
    
    
    protected $fillable = [
        'name',
        'image',
        'link',
        'start_date',
        'end_date',
        'status',
    ];
}
