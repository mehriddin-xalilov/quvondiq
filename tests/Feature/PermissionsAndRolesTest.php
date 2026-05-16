<?php

namespace Tests\Feature;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionsAndRolesTest extends TestCase
{
    public function test_seeder_creates_expected_permissions(): void
    {
        $expected = [
            'dashboard.view',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'templates.view', 'templates.create', 'templates.edit', 'templates.delete',
            'documents.generate',
            'lookups.view', 'lookups.manage',
        ];

        foreach ($expected as $name) {
            $this->assertTrue(
                Permission::where('name', $name)->exists(),
                "Permission '$name' topilmadi"
            );
        }
    }

    public function test_seeder_creates_three_roles(): void
    {
        $this->assertTrue(Role::where('name', 'Super Admin')->exists());
        $this->assertTrue(Role::where('name', 'Admin')->exists());
        $this->assertTrue(Role::where('name', 'Operator')->exists());
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $superAdmin = Role::findByName('Super Admin');
        $this->assertSame(Permission::count(), $superAdmin->permissions()->count());
    }

    public function test_operator_has_only_view_and_generate(): void
    {
        $operator = Role::findByName('Operator');
        $perms = $operator->permissions->pluck('name')->all();

        $this->assertContains('documents.generate', $perms);
        $this->assertContains('templates.view', $perms);
        $this->assertNotContains('templates.create', $perms);
        $this->assertNotContains('users.view', $perms);
    }
}