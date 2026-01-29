<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable = [
        
        'size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function designs()
    {
        return $this->belongsToMany(Design::class, 'design_measurement')
            ->withTimestamps();
    }
}
