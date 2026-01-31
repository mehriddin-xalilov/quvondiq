<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StockMovementPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions for stock movements
        $permissions = [
            'view-stock-movements',
            'create-stock-movements',
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

        // Assign permissions to Warehouse Worker role
        $warehouseRole = Role::where('name', 'Warehouse Worker')->first();
        if ($warehouseRole) {
            $warehouseRole->givePermissionTo($permissions);
        }
    }
}
