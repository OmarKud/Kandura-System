<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash ;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
              "name" =>"guest",
              "email"=>"guest@gmail.com" ,
              "password"=>Hash::make("password"),
              "phone"=>"099123413",
              "role_id"=>"1"

            ],

             [
              "name" =>"user",
              "email"=>"user@gmail.com" ,
              "password"=>Hash::make("password123"),
              "phone"=>"099123567",
              "role_id"=>"1"

            ],
            [
              "name" =>"admin",
              "email"=>"admin@gmail.com" ,
              "password"=>Hash::make("password1234"),
              "phone"=>"09912084",
              "role_id"=>"1"

            ],
            [
              "name" =>"superadmin",
              "email"=>"super@gmail.com" ,
              "password"=>Hash::make("password12385"),
              "phone"=>"099936567",
              "role_id"=>"1"

            ],



        ]);

         $admin = Role::where([
            'guard_name' => 'api',
            'name' => 'admin'
        ])->first();

        $user = Role::where([
            'guard_name' => 'api',
            'name' => 'user'
        ])->first();

        $guest = Role::where([
            'guard_name' => 'api',
            'name' => 'guest'
        ])->first();

          $SuperAdmin = Role::where([
            'guard_name' => 'api',
            'name' => 'superadmin'
        ])->first();


        $admin = User::where('email', 'admin@gmail.com')->first()->assignRole($admin);
        $editor = User::where('email', 'guest@gmail.com')->first()->assignRole($guest);
        $viewer = User::where("email", 'user@gmail.com')->first()->assignRole($user);
        $viewer = User::where("email", 'super@gmail.com')->first()->assignRole($SuperAdmin);

        
    }
}
