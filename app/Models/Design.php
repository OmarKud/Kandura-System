<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'user_id',
        'measurement_id',
        'name',
        'description',
        'status',
        'price',
    ];

    
  public function images()
    {
        return $this->hasMany(DesignImage::class);
    }
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function measurements()
{
    return $this->belongsToMany(Measurement::class, 'design_measurement')
        ->withTimestamps();
}

    public function optionSelections()
    {
        return $this->hasMany(DesignOptionSelection::class);
    }

  
    public function designOptions()
    {
        return $this->belongsToMany(DesignOption::class, 'design_option_selections')
           ;
    }
public function allowedOptions()
{
    return $this->belongsToMany(
        DesignOption::class,
        'design_option_selections',   // 
        'design_id',
        'design_option_id'
    );
}

}
