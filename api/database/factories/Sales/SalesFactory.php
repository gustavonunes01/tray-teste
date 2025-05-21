<?php

namespace Database\Factories\Sales;

use App\Models\Sales\Sales;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesFactory extends Factory
{
    protected $model = Sales::class;

    public function definition(): array
    {

        return [
            'name' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'commission_value' => $this->faker->randomFloat(2, 10, 100),
            'seller_id' => User::factory(),
            'external_id' => $this->faker->uuid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
