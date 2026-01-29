<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function complet($data, $message = "Success", $code = 200)
    {
        return response()->json([
            "status_code" => $code,
            "message" => $message,
            "data" => $data
        ], $code);
    }
}
