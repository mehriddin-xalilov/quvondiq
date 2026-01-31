<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class WarehouseStockPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create permission for warehouse stocks
        $permission = Permission::firstOrCreate(['name' => 'view-warehouse-stocks']);

        // Assign permission to Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }

        // Assign permission to Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permission);
        }

        // Assign permission to Warehouse Worker role
        $warehouseRole = Role::where('name', 'Warehouse Worker')->first();
        if ($warehouseRole) {
            $warehouseRole->givePermissionTo($permission);
        }

        // Assign permission to Manager role
        $managerRole = Role::where('name', 'Manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo($permission);
        }
    }
}
