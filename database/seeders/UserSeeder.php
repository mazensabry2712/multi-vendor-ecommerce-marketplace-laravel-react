<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        User::factory()->create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => '123',

        ])->assignRole(RolesEnum::User->value);


        User::factory( )->create([
            'name' => 'Vendor',
            'email' => 'vendor@vendor.com',
            'password' => '123',

        ])->assignRole(RolesEnum::Vendor->value);


        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => '123',
        ])->assignRole(RolesEnum::Admin->value);








    }
}
