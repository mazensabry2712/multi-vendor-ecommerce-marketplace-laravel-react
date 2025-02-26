<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Enums\PermissionsEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::create(['name' => RolesEnum::User->value]);
        $vendorRole = Role::create(['name' => RolesEnum::Vendor->value]);
        $adminRole = Role::create(['name' => RolesEnum::Admin->value]);





        $approveVendor =Permission::create([
            'name' => PermissionsEnum::ApproveVendor->value
        ]);
        $sellProducts =Permission::create([
            'name' => PermissionsEnum::SellProducts->value
        ]);
        $buyProducts =Permission::create([
            'name' => PermissionsEnum::BuyProducts->value
        ]);





        $userRole->syncPermissions([
            $buyProducts
        ]);

        $vendorRole->syncPermissions([
            $sellProducts, $buyProducts
        ]);

        $adminRole->syncPermissions([
            $approveVendor, $sellProducts, $buyProducts
        ]);





    }
}
