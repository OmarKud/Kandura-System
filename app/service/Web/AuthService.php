<?php

namespace App\service\Web;

use App\Models\User;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function register($request, $role = 2)
    {
        $request["password"] =Hash::make($request['password']);
        // $request["role_id"]=$role;
        $user = User::create(array_merge(
            $request,
            [
                "role_id" => $role

            ]

        ));
       
        return $user;

    }
    public function login($request)
    {
        if (
            !Auth::attempt([
                'email' => $request['email'],
                'password' => $request['password'],
            ])
        ) {
            throw new Exception('Invalid credentials', 400);
        }

        $user = Auth::user();

       

        return $user;
    }
}
