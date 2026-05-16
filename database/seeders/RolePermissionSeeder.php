<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Document templates
            'templates.view',
            'templates.create',
            'templates.edit',
            'templates.delete',

            // Document generation
            'documents.generate',

            // Lookups (regions, districts, professions)
            'lookups.view',
            'lookups.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin — barcha huquqlar
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin — shablon va hujjat boshqaruvi
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'dashboard.view',
            'templates.view', 'templates.create', 'templates.edit', 'templates.delete',
            'documents.generate',
            'lookups.view', 'lookups.manage',
        ]);

        // Operator — faqat hujjat generatsiya qilish
        $operator = Role::firstOrCreate(['name' => 'Operator']);
        $operator->syncPermissions([
            'dashboard.view',
            'templates.view',
            'documents.generate',
            'lookups.view',
        ]);

        $this->command->info('Roles and permissions seeded.');
    }
}