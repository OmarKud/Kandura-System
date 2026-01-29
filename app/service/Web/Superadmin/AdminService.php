<?php

namespace App\service\Web\Superadmin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminService
{

public function updateAdminRole(User $user, int $roleId): User
{
    return DB::transaction(function () use ($user, $roleId) {

        $role = Role::query()
            ->where('guard_name','api')->whereNot("id",4)
            ->whereKey($roleId)
            ->firstOrFail();

        // ممنوع رول user/guest للأدمن
        if (in_array($role->name, ['user','guest'], true)) {
            abort(422, 'Invalid role for admin dashboard.');
        }

        $user->update(['role_id' => $role->id]);
        $user->syncRoles([$role]);

        return $user;
    });
}

public function deleteAdmin(User $admin): void
{
    DB::transaction(function () use ($admin) {

        if ((int)$admin->id === (int)auth()->id()) {
            abort(422, 'You cannot delete your own account.');
        }

        if ($admin->hasRole('superadmin') || (int)$admin->role_id === 4) {
            abort(422, 'You cannot delete a superadmin account.');
        }

        $admin->syncRoles([]);

        $admin->delete();
    });
}
    public function paginateAdmins(string $q = '', int $perPage = 15)
    {
        return User::query()
            ->select(['users.id','users.name','users.email','users.phone','users.status','users.role_id','users.created_at'])
            ->with(['roles:id,name']) // Spatie pivot roles
            ->when($q !== '', function ($w) use ($q) {
                $w->where(function ($x) use ($q) {
                    $x->where('users.name','like',"%{$q}%")
                      ->orWhere('users.email','like',"%{$q}%")
                      ->orWhere('users.phone','like',"%{$q}%");
                });
            })
            ->whereNotIn('users.role_id', function ($sub) {
                $sub->select('id')
                    ->from('roles')
                    ->where('guard_name','api')
                    ->whereIn('name', ['user','guest']);
            })
            ->orderByDesc('users.id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function rolesForSelect()
    {
        return Role::query()
            ->where('guard_name','api')->whereNot("id",4)
            ->whereNotIn('name', ['user','guest']) // roles that can access dashboard
            ->orderBy('name')
            ->get(['id','name']);
    }

    public function createAdmin(array $data, int $roleId): User
    {
        return DB::transaction(function () use ($data, $roleId) {

            $role = Role::query()
                ->where('guard_name','api')
                ->whereKey($roleId)
                ->firstOrFail();

            // ممنوع إنشاء Admin بدور user/guest
            if (in_array($role->name, ['user','guest'], true)) {
                abort(422, 'Invalid role for admin dashboard.');
            }

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'],
                'status'   => $data['status'] ?? 'active',
                'role_id'  => $role->id, // ديناميكي من جدول roles
                'password' => Hash::make($data['password']),
            ]);

            // مهم جداً: حتى Spatie permissions تشتغل
            $user->syncRoles([$role]);

            return $user;
        });
    }

    
}
