<?php

namespace Database\Seeders;

use App\Models\Commission;
use Illuminate\Database\Seeder;

class CommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Commission::insert([
            [
                'title' => 'consultant',
                'profit' => '3',
                'points' => '100',
                'gift' => '500',
                'ptp' => null,
            ],
            [
                'title' => 'distributor',
                'profit' => '6',
                'points' => '300',
                'gift' => '500',
                'ptp' => null,
            ],
            [
                'title' => 'senior distributor',
                'profit' => '9',
                'points' => '600',
                'gift' => '500',
                'ptp' => null,
            ],
            [
                'title' => 'supervisor',
                'profit' => '12',
                'points' => '1200',
                'gift' => '500',
                'ptp' => 10,
            ],
        ]);
    }
}
