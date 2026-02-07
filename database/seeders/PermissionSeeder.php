<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ مهم جداً مع Spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // ✅ كل الصلاحيات الأساسية
        $permissions = [
            'view welcome',
            'view details comuncation',
            'view design',
            'create profile',
            'edit my profile',
            'create addres',
            'edit addres',
            'create design',
            'update design',
            'delete design',
            'show balance',
            'show transaction',
            'show notifacation',
            'rate',

            'view users',
            'edit status account',
            'delete account',
            'show&edit status order',
            'edit all design',
            'delete all design',
            'view all order',
            'create copon',
            'edit copon',
            'delete copon',
            'accept rate',
            'send notification',
            'to withdraw',
            'deposite',

            'edit admin',
            'delete admin',
            'mange system',
            'show report',
            'mange role',

            'create design option',
            'update design option',
            'delete design option',
        ];

        $rows = array_map(fn ($name) => [
            'name' => $name,
            'guard_name' => 'api',
        ], $permissions);

        Permission::upsert($rows, ['name', 'guard_name'], []);

        $guestPerms = [
            'view welcome',
            'view design',
            'view details comuncation',
        ];

        $userPerms = [
            'view design',
            'create profile',
            'edit my profile',
            'create addres',
            'edit addres',
            'create design',
            'update design',
            'delete design',
            'show balance',
            'show transaction',
            'show notifacation',
            'rate',
        ];

        $adminPerms = [
            'view design',
            'edit addres',
            'create design',
            'update design',
            'delete design',
            'show transaction',
            'view users',
            'edit status account',
            'delete account',
            'show&edit status order',
            'edit all design',
            'delete all design',
            'view all order',
            'create copon',
            'edit copon',
            'delete copon',
            'accept rate',
            'send notification',
            'to withdraw',
            'deposite',

            'create design option',
            'update design option',
            'delete design option',
        ];

        $guest = Role::firstOrCreate(['name' => 'guest', 'guard_name' => 'api']);
        $user  = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $super = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'api']);

        $guest->syncPermissions($guestPerms);
        $user->syncPermissions($userPerms);
        $admin->syncPermissions($adminPerms);

        $super->syncPermissions(Permission::where('guard_name', 'api')->get());
    }
}
