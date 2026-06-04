<?php

namespace Database\Factories;

use App\Models\Fridge;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fridge>
 */
class FridgeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement(['Kitchen', 'Garage freezer', 'Summer house', 'Office']),
        ];
    }
}
