<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@sellmarket.test',
            'role' => 'super_admin',
            'status' => true,
        ]);

        Admin::factory()->count(3)->create([
            'status' => true,
        ]);
    }
}
