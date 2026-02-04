<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AdminPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissionNames = [
            'admin.user.manage',
            'admin.address.manage',
            'admin.design.manage',
            'admin.design_option.manage',
            'admin.wallet.manage',
            'admin.order.manage',
            'admin.coupon.manage',
            'admin.invoice.manage',
            'admin.review.manage',

        
        ];

        foreach ($permissionNames as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'api',
            ]);
        }

        $users = User::whereIn('role_id', [3, 4])->get();

        foreach ($users as $u) {
            foreach ($permissionNames as $permName) {
                $u->givePermissionTo($permName); // now it will search in guard api (correct)
            }
        }
    }
}
