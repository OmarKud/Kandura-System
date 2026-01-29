<?php

namespace App\service;

use App\Http\Controllers\Api\User\WalletController;
use App\Models\User;
use App\Service\User\ImageService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\throwException;

class AuthService
{
    /**
     * Create a new class instance.
     */
    protected $walletcontroller;
    public function __construct(WalletController $walletcontroller)
    {
        $this->walletcontroller = $walletcontroller;
    }
    public function register($request, $role = 1)
    {
        $request["password"] = Hash::make($request['password']);
        // $request["role_id"]=$role;
        $user = User::create(array_merge(
            $request,
            [
                "role_id" => $role

            ]

        ));
        $user->assignRole("user");
        if ($request->hasFile('profile_image')) {
            $path = 'profile_images';
            $file = $request->file('profile_image');

            $user->profileImage()->updateOrCreate(
                ['user_id' => $user->id],
                ['url' => ImageService::uploadImage($file, $path)]
            );
        }

        $user_id = $user->id;
        $this->walletcontroller->create($user_id);
        $token = $user->createToken("API Token")->plainTextToken;

        $user->access_token = $token;
        return $user->load("profileImage");

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

        $token = $user->createToken('API Token')->plainTextToken;

        $user->access_token = $token;

        return $user->load("profileImage");
    }
    public function profile()
    {
        return Auth::user();
    }

    public function edit_profile($request)
    {

        $user = $request->user();

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->filled('password')) {
            if (!Hash::check($request->input('old_password'), $user->password)) {
                throw new Exception("password incorect", 402);
            }

            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);
        return $user;




    }
    public function delete_profile()
    {
        $id = Auth::id();
        $user = User::findOrFail($id);
        return $user->delete();


    }

}
