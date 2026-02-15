<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pathcore.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create teacher user
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@pathcore.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_TEACHER,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create accountant user
        User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@pathcore.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ACCOUNTANT,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create an inactive user for testing
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@pathcore.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_TEACHER,
            'is_active' => false,
            'email_verified_at' => now(),
        ]);
    }
}
