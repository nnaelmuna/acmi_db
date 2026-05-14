<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    // Tentukan nama tabel jika berbeda (opsional, Laravel otomatis anggap 'members')
    protected $table = 'members';

    // Daftarkan kolom yang boleh diisi secara massal
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'industry',
        'position',
        'company_url',
        'linkedin_url',
        'status',
    ];

    // Jika kamu punya kolom yang harus bertipe date
    protected $dates = ['deleted_at'];
}