<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Support\Freshness;
use Tests\TestCase;

class FreshnessTest extends TestCase
{
    public function test_old_items_are_expired(): void
    {
        $item = new Item(['best_before' => now()->subDays(5)]);

        $this->assertSame(Freshness::EXPIRED, Freshness::for($item));
    }

    public function test_items_expiring_tomorrow_should_be_used_soon(): void
    {
        $item = new Item(['best_before' => now()->addDay()]);

        $this->assertSame(Freshness::USE_SOON, Freshness::for($item));
    }

    public function test_items_far_in_the_future_are_fresh(): void
    {
        $item = new Item(['best_before' => now()->addDays(10)]);

        $this->assertSame(Freshness::FRESH, Freshness::for($item));
    }
}
