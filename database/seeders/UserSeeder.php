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


        User::factory(10)->create([
            'name' => 'user',
            'email' => 'user@user.com',
        ])->assignRole(RolesEnum::User->value);


        User::factory(count: 10)->create([
            'name' => 'Vendor',
            'email' => 'vendor@vendor.com',
        ])->assignRole(RolesEnum::Vendor->value);


        User::factory(10)->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ])->assignRole(RolesEnum::Admin->value);








    }
}
