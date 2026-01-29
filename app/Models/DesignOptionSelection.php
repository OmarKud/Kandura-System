<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignOptionSelection extends Model
{
   protected $fillable = [
        'design_id',
        'design_option_id',
        'notes',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function designOption()
    {
        return $this->belongsTo(DesignOption::class);
    }
}
