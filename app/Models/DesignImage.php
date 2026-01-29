<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignImage extends Model
{
     protected $fillable = [
        'design_id',
        'url',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->url);
    }
}
