<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\service\Web\AuthService;
use Illuminate\Support\Facades\Auth;
use Exception;

class SuperAdminAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // صفحة login للـ SUPER ADMIN
    public function showLoginForm()
    {
        return view('super.auth.login'); // خليه مسار مختلف عن admin لو بدك
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request);

            // super admin لازم role_id = 4
            if ($user->role_id !== 4) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'هذا الحساب ليس Super Admin'])
                    ->withInput();
            }

            return redirect()->route('dashboard.welcome');

        } catch (Exception $e) {
            return back()
                ->withErrors(['email' => 'بيانات الدخول غير صحيحة'])
                ->withInput();
        }
    }

    // صفحة register للـ SUPER ADMIN
    public function showRegisterForm()
    {
        return view('super.auth.register');
    }

    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();

        // هون منمرر role = 4
        $user = $this->authService->register($data, 4);

        Auth::login($user);

        return redirect()->route('dashboard.welcome');
    }
}
