<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VendorOrder>
 */
class VendorOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => '1107',
            'seller_id' => '1107',
            'vendor_id' => '1',
            'order_no' => $this->faker->unique()->numerify('ORD-#####'),
            'points' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->randomFloat(2, 0.5, 50), // in kg
            'subtotal' => $this->faker->randomFloat(2, 10, 1000),
            'shippingcharges' => $this->faker->randomFloat(2, 5, 50),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'total_bill' => $this->faker->randomFloat(2, 50, 2000),
            'commission_amount' => $this->faker->randomFloat(2, 5, 200),
            'commission' => $this->faker->randomFloat(2, 1, 20),
            'vendor_amount' => $this->faker->randomFloat(2, 20, 1000),
            'payment_by' => $this->faker->randomElement(['0', '1', '2']),
            'status' => $this->faker->randomElement(['3', '6']),
            'is_order_handle_by_admin' => $this->faker->boolean(),
            'delivery_by' => $this->faker->company(),
            'delivery_trackingid' => $this->faker->uuid(),
            'order_return' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('2024-09-01', '2024-09-31'),
            'updated_at' => now(),
            'delivery_at' => $this->faker->dateTimeBetween('+1 days', '+10 days'),
        ];
    }
}
