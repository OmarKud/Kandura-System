<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\service\AuthService;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authservice;
    public function __construct(AuthService $authservice)
    {
        $this->authservice = $authservice;

    }
    public function register(StoreUserRequest $request,$role =2){
        $user= $this->authservice->register($request->validated(),$role);
        return $this->complet(new UserResource($user), "User registered successfully") ;

    } public function login(LoginRequest $request){
        $attr = $request->validated();
        $user = $this->authservice->login($request);
        return $this->complet(new UserResource($user), "User logged in successfully");
    } 
    public function profile() {
        $user = $this->authservice->profile();
        return $this->complet(new ProfileResource($user));
    }
    public function edit_profile(UpdateProfileRequest $request){
            if (! $request->hasAny(['name', 'email', 'phone', 'password'])) {
    return response()->json('enter what you want to edit', 400);
}
         $user = $this->authservice->edit_profile($request);
        
        return $this->complet(new ProfileResource($user), "User updated successfully");

    }
    public function delete_profile(){
          $user = $this->authservice->delete_profile();
        
        return response()->json("user deleted succes");

    }

}
