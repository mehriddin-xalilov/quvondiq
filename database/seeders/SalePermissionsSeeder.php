<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SalePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions for sales
        $permissions = [
            'view-sales',
            'create-sales',
            'delete-sales',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        // Assign permissions to Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Assign permissions to Manager role
        $managerRole = Role::where('name', 'Manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo(['view-sales', 'create-sales']);
        }

        // Assign permissions to Cashier role (if exists)
        $cashierRole = Role::where('name', 'Cashier')->first();
        if ($cashierRole) {
            $cashierRole->givePermissionTo(['view-sales', 'create-sales']);
        }
    }
}
