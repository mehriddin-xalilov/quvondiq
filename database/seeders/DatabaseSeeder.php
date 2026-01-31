<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run role and permission seeder first
        $this->call(RolePermissionSeeder::class);

        // Create Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@yemdokoni.uz',
            'password' => Hash::make('password'),
            'phone' => '+998901234567',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        // Create Admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'manager@yemdokoni.uz',
            'password' => Hash::make('password'),
            'phone' => '+998901234568',
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        // Create Sotuvchi (Salesperson)
        $salesperson = User::create([
            'name' => 'Sotuvchi',
            'email' => 'sotuvchi@yemdokoni.uz',
            'password' => Hash::make('password'),
            'phone' => '+998901234569',
            'is_active' => true,
        ]);
        $salesperson->assignRole('Sotuvchi');

        // Create Omborchi (Warehouse keeper)
        $warehouse = User::create([
            'name' => 'Omborchi',
            'email' => 'omborchi@yemdokoni.uz',
            'password' => Hash::make('password'),
            'phone' => '+998901234570',
            'is_active' => true,
        ]);
        $warehouse->assignRole('Omborchi');

        $this->command->info('Default users created successfully!');
        $this->command->info('Super Admin: admin@yemdokoni.uz / password');
        $this->command->info('Admin: manager@yemdokoni.uz / password');
        $this->command->info('Sotuvchi: sotuvchi@yemdokoni.uz / password');
        $this->command->info('Omborchi: omborchi@yemdokoni.uz / password');

        // Call demo data seeder (optional)
        if ($this->command->confirm('Do you want to seed demo data?', false)) {
            $this->call(DemoDataSeeder::class);
        }
    }
}
