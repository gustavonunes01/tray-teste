<?php

namespace Database\Factories;

use App\Enums\ProfileTypesEnum;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'profile_id' => ProfileTypesEnum::SELLER,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
