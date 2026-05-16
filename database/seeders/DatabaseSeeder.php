<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $superAdmin = User::firstOrCreate(
                ['email' => 'admin@example.uz'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'phone' => '+998901234567',
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('Super Admin');

        $this->call(RegionSeeder::class);
        $this->call(ProfessionSeeder::class);
        $this->call(DocumentTemplateSeeder::class);

        $this->command->info('Login: admin@example.uz / password');
    }
}
