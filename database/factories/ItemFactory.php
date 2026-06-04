<?php

namespace Database\Factories;

use App\Models\Fridge;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fridge_id' => Fridge::factory(),
            'name' => fake()->randomElement(['Milk', 'Eggs', 'Butter', 'Cheese', 'Yoghurt', 'Carrots', 'Ham', 'Orange juice']),
            'quantity' => 1,
            'unit' => null,
            'best_before' => now()->addDays(fake()->numberBetween(-3, 14))->toDateString(),
        ];
    }

    public function used(): static
    {
        return $this->state(fn () => ['used_at' => now()->subDays(fake()->numberBetween(1, 20))]);
    }
}
