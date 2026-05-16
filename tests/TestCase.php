<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    protected function makeUser(string $role = 'Super Admin', array $attrs = []): User
    {
        $user = User::create(array_merge([
            'name'      => 'Test '.$role,
            'email'     => strtolower($role).'+'.uniqid().'@test.uz',
            'password'  => Hash::make('secret123'),
            'phone'     => '+998900000000',
            'is_active' => true,
        ], $attrs));

        $user->assignRole($role);

        return $user;
    }

    protected function actingAsAdmin(): User
    {
        $user = $this->makeUser('Super Admin');
        $this->actingAs($user);
        return $user;
    }

    protected function actingAsOperator(): User
    {
        $user = $this->makeUser('Operator');
        $this->actingAs($user);
        return $user;
    }
}