<?php

namespace App\Service\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function uploadImage($file, $path)
    {
        $filename = Str::uuid() . '.' . $file->extension();

        $path = $file->storeAs($path, $filename, 'public');

        Storage::disk('public')->setVisibility($path, 'public');

        return $path;
    }
}
