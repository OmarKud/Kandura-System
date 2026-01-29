<?php

namespace App\service\User;

use App\Models\Measurement;
use Illuminate\Support\Facades\Auth;

class measurementservic
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }
    public function create(array $request)
    {
        $userId = Auth::id();

        $measurement = Measurement::create(array_merge($request, [
            'user_id' => $userId,

        ]));

        return $measurement;

    }
}
