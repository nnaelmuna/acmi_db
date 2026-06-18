<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company_name',
        'industry',
        'position',
        'company_url',
        'linkedin_url',
        'status',
        'sub_status',
        'employee_size',
        'annual_revenue',
        'message',
    ];

    // Jika kamu punya kolom yang harus bertipe date
    protected $dates = ['deleted_at'];
}