<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DesignOption extends Model
{
    use HasTranslations;
    protected $fillable = [
        "name",
        "type"
    ];
    protected $translatable  = ["name"];
    protected $casts = [
    'name' => 'array',
];
}
