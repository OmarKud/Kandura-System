<?php

namespace App\service\Web\Superadmin;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function paginate(string $q = '', int $perPage = 15)
    {
        return Role::query()
            ->where('guard_name', 'api')->whereNot("id",4)
            ->when($q !== '', fn($w) => $w->where('name', 'like', "%{$q}%"))
            ->withCount('permissions')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function allPermissions(): Collection
    {
        return Permission::query()
            ->where('guard_name', 'api')->whereNot("id",62)->whereNot("id",63)->whereNot("id",64)
            ->orderBy('name')
            ->get(['id','name']);
    }

    public function create(string $name, array $permissionIds): Role
    {
        return DB::transaction(function () use ($name, $permissionIds) {
            $role = Role::create([
                'name' => trim($name),
                'guard_name' => 'api',
            ]);

            $permissions = Permission::query()
                ->where('guard_name', 'api')
                ->whereIn('id', $permissionIds)
                ->get();

            $role->syncPermissions($permissions);

            return $role;
        });
    }
    public function updateAdminRole(User $user, int $roleId): User
{
    return DB::transaction(function () use ($user, $roleId) {

        $role = Role::query()
            ->where('guard_name','api')
            ->whereKey($roleId)
            ->firstOrFail();

        // ممنوع رول user/guest للأدمن
        if (in_array($role->name, ['user','guest'], true)) {
            abort(422, 'Invalid role for admin dashboard.');
        }

        // حفظ الرول ديناميكياً في users.role_id
        $user->update([
            'role_id' => $role->id,
        ]);

        $user->syncRoles([$role]);

        return $user;
    });
}


    public function update(Role $role, string $name, array $permissionIds): Role
    {
        return DB::transaction(function () use ($role, $name, $permissionIds) {
            $role->update(['name' => trim($name)]);

            $permissions = Permission::query()
                ->where('guard_name', 'api')
                ->whereIn('id', $permissionIds)
                ->get();

            $role->syncPermissions($permissions);

            return $role;
        });
    }
}
