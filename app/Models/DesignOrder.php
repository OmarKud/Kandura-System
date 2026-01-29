<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignOrder extends Model
{
protected $fillable = ["order_id","design_id","measurement_id"];
    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function design(){
        return $this->belongsTo(Design::class);
    }
    public function options()
    {
return $this->belongsToMany(
        DesignOption::class,
        'design_orders_sellection',   
        'design_orders_id',       
        'design_option_id'            
    );}
    public function measurement()
{
    return $this->belongsTo(Measurement::class);
}
}