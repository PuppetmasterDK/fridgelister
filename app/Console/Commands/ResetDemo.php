<?php

namespace App\Console\Commands;

use App\Models\Fridge;
use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Resets the public demo every night so visitors always see a lively fridge.
 */
class ResetDemo extends Command
{
    protected $signature = 'fridge:demo {--extra=5 : Random items to add on top of the seed data}';

    protected $description = 'Reset the demo database and add some random items';

    public function handle(): int
    {
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);

        $faker = Faker::create();
        $fridge = Fridge::first();

        for ($i = 0; $i < (int) $this->option('extra'); $i++) {
            $fridge->items()->create([
                'name' => ucfirst($faker->word()),
                'quantity' => $faker->numberBetween(1, 4),
                'best_before' => now()->addDays($faker->numberBetween(-2, 12))->toDateString(),
                'added_by' => $fridge->user_id,
            ]);
        }

        $this->info('Demo reset.');

        return self::SUCCESS;
    }
}
