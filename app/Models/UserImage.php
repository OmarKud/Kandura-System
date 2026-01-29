<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserImage extends Model
{
    protected $fillable = [
        'user_id',
        'url',
    ];

    protected $appends = ['full_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullUrlAttribute(): ?string
    {
        $u = (string) ($this->url ?? '');
        if ($u === '') return null;

        if (Str::startsWith($u, ['http://', 'https://'])) {
            return $u;
        }

        if (Str::startsWith($u, ['/'])) {
            return $u;
        }

        return Storage::url($u); // يعطي /storage/...
    }
}
