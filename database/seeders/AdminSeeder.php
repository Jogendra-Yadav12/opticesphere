<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@sellmarket.test'],
            [
                'name' => 'Super Admin',
                'role' => 'super_admin',
                'status' => true,
                'password' => 'password', // Don't use bcrypt because the Admin model has a 'hashed' cast which hashes it automatically
            ]
        );
    }
}
