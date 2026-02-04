<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\service\Web\AuthService;
use Illuminate\Support\Facades\Auth;
use Exception;
use Spatie\Permission\Models\Role;

class AdminAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('admin.auth.login'); // 
    }

    // تنفيذ تسجيل الدخول للـ ADMIN (role_id = 3 أو 4)
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request);

            // هون صار Auth::user() موجود و session شغّالة
         $roleName = Role::where('guard_name','api')->whereKey($user->role_id)->value('name');

// امنع user/guest فقط
if (in_array($roleName, ['user','guest'], true)) {
    Auth::logout();
    return back()->withErrors(['email' => 'No permission to access dashboard.'])->withInput();
}

            return redirect()->route('dashboard.welcome');

        } catch (Exception $e) {
            return back()
                ->withErrors(['email' => 'بيانات الدخول غير صحيحة'])
                ->withInput();
        }
    }

    // عرض صفحة التسجيل
    public function showRegisterForm()
    {
        return view('admin.auth.register'); // Blade للتسجيل
    }

    // تنفيذ التسجيل كـ ADMIN (role_id = 3)
    public function register(StoreUserRequest $request)
    {
        // StoreUserRequest بيرجع array جاهزة
        $data = $request->validated();

        // هون منمرر role = 3
        $user = $this->authService->register($data, 3);

        // login للويب (session)
        Auth::login($user);

        return redirect()->route('dashboard.welcome');
    }
}
