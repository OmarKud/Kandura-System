<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SuperAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionNames = [
    'admin.superadmin.permission.manage',
            'admin.superadmin.role.manage',
            'admin.superadmin.admin.manage',
        ];
  foreach ($permissionNames as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'api',
            ]);
        }

        $users = User::where('role_id',  4)->get();

        foreach ($users as $u) {
            foreach ($permissionNames as $permName) {
                $u->givePermissionTo($permName); // now it will search in guard api (correct)
            }
        }
    }
}