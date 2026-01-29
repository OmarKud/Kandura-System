<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Address extends Model
{
    use HasTranslations;

    public $translatable = ['name', 'description'];
    protected $fillable = [
        "user_id",
        "city",
        "street",
        "build",
        "latitude",
        "longitude"
    ];
    public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

}
