<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Ini kuncinya! Kita kasih izin kolom mana aja yang boleh diisi
 protected $fillable = [
    'activity_type', 
    'description',
    'user_id'
];

    // Opsional: Kalau kamu mau nampilin siapa yang ngelakuin aksi, tambahin relasi ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}