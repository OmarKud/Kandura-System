<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_value',
        'usage_limit',
        'used_count',
        'is_active',
        "discount_type"
        ,'expiry_date'
    ];

     protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'expiry_date' => 'date'
    ];
}
