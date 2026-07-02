<?php

namespace Database\Factories;

use App\Models\Fridge;
use App\Models\Share;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Share>
 */
class ShareFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fridge_id' => Fridge::factory(),
            'email' => fake()->unique()->safeEmail(),
            'status' => 'pending',
        ];
    }
}
