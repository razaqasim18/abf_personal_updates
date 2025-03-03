<?php

namespace Database\Seeders;

use App\Models\VendorOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 fake orders
        VendorOrder::factory()->count(50)->create();
    }
}
