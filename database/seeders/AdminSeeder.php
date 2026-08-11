<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Only adding the specific admin login details here (no extra dummy admins)
        Admin::updateOrCreate(
            ['email' => 'admin@sellmarket.test'],
            [
                'name' => 'Super Admin',
                'role' => 'super_admin',
                'status' => true,
                'password' => 'password',
            ]
        );
    }
}
