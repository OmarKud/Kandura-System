<?php

namespace App\service\Web\Superadmin;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function paginate(string $q = '', int $perPage = 15): LengthAwarePaginator
{
    $excluded = [
        'admin.superadmin.permission.manage',
        'admin.superadmin.role.manage',
        'admin.superadmin.admin.manage',
    ];

    return Permission::query()
        ->where('guard_name', 'api')
        ->whereNotIn('name', $excluded)
        ->when($q !== '', fn ($w) => $w->where('name', 'like', "%{$q}%"))
        ->orderBy('name')
        ->paginate($perPage)
        ->withQueryString();
}


    public function create(string $name): Permission
    {
        return Permission::create([
            'name' => trim($name),
            'guard_name' => 'api',
        ]);
    }

    public function update(Permission $permission, string $name): Permission
    {
        $permission->update(['name' => trim($name)]);
        return $permission;
    }

    public function delete(Permission $permission): void
    {
        $permission->delete();
    }
}
