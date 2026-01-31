<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // Users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Customers
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',
            
            // Categories
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',
            
            // Products
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',
            
            // Warehouse
            'view-warehouse',
            'stock-in',
            'stock-out',
            'adjust-stock',
            'view-stock-movements',
            
            // Sales
            'view-sales',
            'create-sales',
            'edit-sales',
            'delete-sales',
            'view-sale-items',
            
            // Payments & Debts
            'view-debts',
            'create-payments',
            'view-payments',
            'edit-debts',
            
            // Expenses
            'view-expenses',
            'create-expenses',
            'edit-expenses',
            'delete-expenses',
            
            // Reports
            'view-reports',
            'view-financial-reports',
            'export-reports',
            
            // Telegram Orders
            'view-telegram-orders',
            'confirm-telegram-orders',
            'manage-telegram-orders',
            
            // Notes
            'view-notes',
            'create-notes',
            'edit-notes',
            'delete-notes',
            
            // Settings
            'view-settings',
            'edit-settings',

            // Roles
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // 1. Super Admin - Full access
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Admin - Almost full access except user management
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view-dashboard',
            'view-customers', 'create-customers', 'edit-customers', 'delete-customers',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-warehouse', 'stock-in', 'stock-out', 'adjust-stock', 'view-stock-movements',
            'view-sales', 'create-sales', 'edit-sales', 'delete-sales', 'view-sale-items',
            'view-debts', 'create-payments', 'view-payments', 'edit-debts',
            'view-expenses', 'create-expenses', 'edit-expenses', 'delete-expenses',
            'view-reports', 'view-financial-reports', 'export-reports',
            'view-telegram-orders', 'confirm-telegram-orders', 'manage-telegram-orders',
            'view-notes', 'create-notes', 'edit-notes', 'delete-notes',
            'view-settings',
        ]);

        // 3. Manager - Sales, customers, reports
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view-dashboard',
            'view-customers', 'create-customers', 'edit-customers',
            'view-products',
            'view-warehouse', 'view-stock-movements',
            'view-sales', 'create-sales', 'view-sale-items',
            'view-debts', 'create-payments', 'view-payments',
            'view-reports', 'view-financial-reports', 'export-reports',
            'view-telegram-orders', 'confirm-telegram-orders',
            'view-notes', 'create-notes', 'edit-notes',
        ]);

        // 4. Sotuvchi (Salesperson) - Sales and customer management
        $salesperson = Role::firstOrCreate(['name' => 'Sotuvchi']);
        $salesperson->givePermissionTo([
            'view-dashboard',
            'view-customers', 'create-customers', 'edit-customers',
            'view-products',
            'view-warehouse',
            'view-sales', 'create-sales', 'view-sale-items',
            'view-debts', 'create-payments', 'view-payments',
            'view-telegram-orders', 'confirm-telegram-orders',
            'view-notes', 'create-notes',
        ]);

        // 5. Omborchi (Warehouse keeper) - Inventory management
        $warehouseKeeper = Role::firstOrCreate(['name' => 'Omborchi']);
        $warehouseKeeper->givePermissionTo([
            'view-dashboard',
            'view-products',
            'view-warehouse', 'stock-in', 'stock-out', 'adjust-stock', 'view-stock-movements',
            'view-notes', 'create-notes',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
