<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::insert([
            [
                'name' => 'view welcome',
                'guard_name' => 'api'
            ],



            [
                'name' => 'view details comuncation',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view design',
                'guard_name' => 'api'
            ],

            [
                'name' => 'create profile',
                'guard_name' => 'api'
            ],

            [
                'name' => 'edit my profile',
                'guard_name' => 'api'
            ],

            [
                'name' => 'create addres',
                'guard_name' => 'api'
            ],

            [
                'name' => 'edit addres',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create design',
                'guard_name' => 'api'
            ],
            [
                'name' => 'update design',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete design',
                'guard_name' => 'api'
            ],
            [
                'name' => 'show balance',
                'guard_name' => 'api'
            ],
            [
                'name' => 'show transaction',
                'guard_name' => 'api'
            ],
            [
                'name' => 'show notifacation',
                'guard_name' => 'api'
            ],
            [
                'name' => 'rate',
                'guard_name' => 'api'
            ],



            [
                'name' => 'view users',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit status account',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete account',
                'guard_name' => 'api'
            ],
            [
                'name' => 'show&edit status order',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit all design',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete all design',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view all order',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create copon',
                'guard_name' => 'api'
            ],
            [
                'name' => 'edit copon',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete copon',
                'guard_name' => 'api'
            ],
            [
                'name' => 'accept rate',
                'guard_name' => 'api'
            ],
            [
                'name' => 'send notification',
                'guard_name' => 'api'
            ],
            [
                'name' => 'to withdraw',
                'guard_name' => 'api'
            ],
            [
                'name' => 'deposite',
                'guard_name' => 'api'
            ],




            [
                'name' => 'edit admin',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete admin',
                'guard_name' => 'api'
            ],

            [
                'name' => 'mange system',
                'guard_name' => 'api'
            ],
            [
                'name' => 'show report',
                'guard_name' => 'api'
            ],
            [
                'name' => 'mange role',
                'guard_name' => 'api'
            ],



        ]);
        Role::create(['name' => 'guest', 'guard_name' => 'api'])->givePermissionTo([
            'view welcome',
            'view design',
            'view details comuncation',
        ]);
        Role::create(['name' => 'user', 'guard_name' => 'api'])->givePermissionTo([
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
        ]);
        Role::create(['name' => 'admin', 'guard_name' => 'api'])->givePermissionTo([
            'view design'
            ,
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
        ]);
        Role::create(['name' => 'superadmin', 'guard_name' => 'api'])->givePermissionTo(Permission::all());
          $permissions = [
            'create design option',
            'update design option',
            'delete design option',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'api']
            );
        }

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api']
        );

        $adminRole->givePermissionTo($permissions);
    }
}
