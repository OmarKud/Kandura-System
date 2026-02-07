<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "user_id",
        "address_id",
        "price",
        "notes",
        "payment_method",
"status",
'coupon_id',
'final_price',
"discount_amount",
"payment_status"
    ];
    protected $casts = [
    'price' => 'float',
    'final_price' => 'float',
];
    public function user(){
        return $this->belongsTo(User::class);
    }
     public function address(){
        return $this->belongsTo(Address::class);
    }
    public function designs(){
        return $this->belongsToMany(Design::class);
    }

    public function designOrders(){
        return $this->hasMany(DesignOrder::class);
    }
    public function coupon()
{
    return $this->belongsTo(Coupon::class);
}
public function invoice()
{
    return $this->hasOne(Invoice::class);
}

public function review()
{
    return $this->hasOne(Review::class);
}

}
