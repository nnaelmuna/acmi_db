<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    use HasFactory;

    /**
     * Menggunakan guarded kosong agar semua kolom bisa diisi secara mass-assignment.
     * Cocok untuk form yang punya banyak field seperti di Detail Pop-up Modal.
     */
    protected $guarded = [];

    /**
     * Jika nanti lu mau nambahin casting untuk kolom tertentu, 
     * misalnya kolom status biar selalu lowercase atau ada array data.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Scope untuk mempermudah pencarian (Search) di Controller
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('company', 'like', "%{$term}%")
              ->orWhere('industry', 'like', "%{$term}%");
        });
    }

    /**
     * Helper untuk menentukan warna badge status di UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'green',
            'review'   => 'blue',
            'rejected' => 'red',
            default    => 'gray', // untuk status 'requested'
        };
    }
}