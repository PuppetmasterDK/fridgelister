<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Demo data: Grandma's fridge, and a helper who does her shopping.
     * Log in as grandma@example.com or helper@example.com, password "password".
     */
    public function run(): void
    {
        $grandma = User::create([
            'name' => 'Grandma Inge',
            'email' => 'grandma@example.com',
            'password' => Hash::make('password'),
        ]);

        $helper = User::create([
            'name' => 'Mads',
            'email' => 'helper@example.com',
            'password' => Hash::make('password'),
        ]);

        $fridge = $grandma->fridges()->create(['name' => 'Kitchen']);

        $inFridge = [
            ['Milk', 1, 'litre', 1],
            ['Eggs', 6, null, 9],
            ['Butter', 250, 'g', 21],
            ['Cheese', 1, null, 3],
            ['Liver pâté', 1, null, -2],
            ['Yoghurt', 1, 'litre', 0],
            ['Carrots', 1, 'kg', 6],
            ['Jam', 1, 'jar', null],
        ];

        foreach ($inFridge as [$name, $quantity, $unit, $days]) {
            $fridge->items()->create([
                'name' => $name,
                'quantity' => $quantity,
                'unit' => $unit,
                'best_before' => $days === null ? null : now()->addDays($days)->toDateString(),
                'added_by' => $grandma->id,
            ]);
        }

        // What has been used lately, so "What to buy" has something to say.
        $used = [
            ['Rye bread', 2], ['Rye bread', 9], ['Rye bread', 16],
            ['Orange juice', 4], ['Orange juice', 12],
            ['Milk', 6], ['Milk', 13],
            ['Ham', 8],
        ];

        foreach ($used as [$name, $daysAgo]) {
            $fridge->items()->create([
                'name' => $name,
                'quantity' => 1,
                'best_before' => now()->subDays($daysAgo - 3)->toDateString(),
                'used_at' => now()->subDays($daysAgo),
                'added_by' => $helper->id,
            ]);
        }

        $fridge->shares()->create([
            'email' => $helper->email,
            'user_id' => $helper->id,
            'status' => 'accepted',
            'shop_by' => now()->addDays(2)->toDateString(),
            'responded_at' => now()->subDays(20),
        ]);

        $summerHouse = $helper->fridges()->create(['name' => 'Summer house']);
        $summerHouse->shares()->create([
            'email' => $grandma->email,
            'user_id' => $grandma->id,
            'status' => 'pending',
        ]);
    }
}
