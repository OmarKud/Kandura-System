<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role as ModelsRole;

class DashboardUserController extends Controller
{
    private function userRoleId(): int
    {
        // كاش ساعة واحدة
        return Cache::remember('role_user_id', 3600, function () {
            return (int) ModelsRole::query()->where('name', 'user')->value('id');
        });
    }

    private function ensureIsUserRole(User $user): void
    {
        if ((int) $user->role_id !== $this->userRoleId()) {
            abort(404);
        }
    }

    public function index(Request $request)
    {
        $roleId = $this->userRoleId();

        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status'); // active|inactive|null
        $sort = $request->query('sort', 'created_at'); // created_at|name|orders_count
        $dir  = strtolower((string) $request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(10, min($perPage, 50));

        // حماية بسيطة للـ sort
        $allowedSorts = ['created_at', 'name', 'orders_count'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        // Query أساسي واضح
        $query = User::query()
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.status',
                'users.role_id',
                'users.created_at',
            ])
            ->where('users.role_id', $roleId)
            // orders_count بطريقة سليمة بدون علاقة orders()
            ->selectSub(
                Order::query()
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('orders.user_id', 'users.id'),
                'orders_count'
            );

        // فلترة بحث
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                  ->orWhere('users.email', 'like', "%{$q}%")
                  ->orWhere('users.phone', 'like', "%{$q}%");
            });
        }

        // فلترة status
        if (in_array($status, ['active', 'inactive'], true)) {
            $query->where('users.status', $status);
        }

        // eager load للصورة فقط لو الجدول موجود
        if (Schema::hasTable('user_images')) {
            $query->with('profileImage');
        }

        // ترتيب
        if ($sort === 'orders_count') {
            $query->orderBy('orders_count', $dir);
        } else {
            $query->orderBy("users.$sort", $dir);
        }

        $users = $query->paginate($perPage)->withQueryString();

        return view('dashboard.users.index', [
            'users'   => $users,
            'q'       => $q,
            'status'  => $status,
            'sort'    => $sort,
            'dir'     => $dir,
            'perPage' => $perPage,
        ]);
    }

    public function show(User $user)
    {
        $this->ensureIsUserRole($user);

        if (Schema::hasTable('user_images')) {
            $user->load('profileImage');
        }

        $ordersCount = Order::query()->where('user_id', $user->id)->count();
        $paidOrdersCount = Order::query()->where('user_id', $user->id)->where('payment_status', 'paid')->count();

        $recentOrders = Order::query()
            ->select(['id', 'user_id', 'final_price', 'status', 'payment_status', 'created_at'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard.users.show', [
            'user'            => $user,
            'ordersCount'     => $ordersCount,
            'paidOrdersCount' => $paidOrdersCount,
            'recentOrders'    => $recentOrders,
        ]);
    }

    public function updateStatus(Request $request, User $user)
    {
        $this->ensureIsUserRole($user);

        $data = $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        DB::transaction(function () use ($user, $data) {
            $locked = User::query()->whereKey($user->id)->lockForUpdate()->first();
            $locked->status = $data['status'];
            $locked->save();
        });

        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}
