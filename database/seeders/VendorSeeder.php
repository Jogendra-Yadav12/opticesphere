<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Vendor;
use App\Models\VendorDocument;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Admin::where('role', 'super_admin')->first();

        Vendor::factory()->count(10)->create()->each(function (Vendor $vendor) {
            VendorDocument::factory()->count(2)->create([
                'vendor_id' => $vendor->id,
            ]);

            $vendor->user->update([
                'name' => $vendor->store_name,
                'role' => 'seller',
                'status' => 'approved',
            ]);
        });

        Vendor::factory()->count(5)->pending()->create()->each(function (Vendor $vendor) {
            $vendor->user->update([
                'name' => $vendor->store_name,
                'role' => 'seller',
                'status' => 'pending',
            ]);
        });
    }
}
