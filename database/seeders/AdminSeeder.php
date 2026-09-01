<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Add the specific admin login details here (no extra dummy admins)
        Admin::updateOrCreate(
            ['email' => 'admin@sellmarket.test'],
            [
                'name' => 'Super Admin',
                'role' => 'super_admin',
                'status' => true,
                'password' => 'password',
            ]
        );

        // Create the corresponding User record so that authentication works
        User::updateOrCreate(
            ['email' => 'admin@sellmarket.test'],
            [
                'name' => 'Super Admin',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
