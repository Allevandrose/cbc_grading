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
        // Use the common password you requested
        $securePassword = Hash::make('aklebrazain');

        // 1. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@pathcore.com',
            'password' => $securePassword,
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
            'onboarding_completed_at' => now(),
        ]);

        // 2. Create Teacher User
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@pathcore.com',
            'password' => $securePassword,
            'role' => User::ROLE_TEACHER,
            'is_active' => true,
            'email_verified_at' => now(),
            'onboarding_completed_at' => now(),
        ]);

        // 3. Create Accountant User
        User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@pathcore.com',
            'password' => $securePassword,
            'role' => User::ROLE_ACCOUNTANT,
            'is_active' => true,
            'email_verified_at' => now(),
            'onboarding_completed_at' => now(),
        ]);

        // 4. Create an Inactive User for testing
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@pathcore.com',
            'password' => $securePassword,
            'role' => User::ROLE_TEACHER,
            'is_active' => false, // Set to false to test blockages
            'email_verified_at' => now(),
            'onboarding_completed_at' => null, // Inactive users usually haven't finished setup
        ]);
    }
}
