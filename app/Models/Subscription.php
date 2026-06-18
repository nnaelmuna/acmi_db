<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company_name',
        'industry',
        'business_model',
        'transaction_image',
        'start_date',
        'end_date',
        'partner_image',
        'status',
        'sub_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
