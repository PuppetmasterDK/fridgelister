<?php

namespace Tests\Feature;

use App\Models\Fridge;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_an_item(): void
    {
        $fridge = Fridge::factory()->create();

        $this->actingAs($fridge->owner)
            ->post('/fridges/'.$fridge->id.'/items', ['name' => 'Milk', 'best_before' => now()->addDays(5)->toDateString()])
            ->assertRedirect();

        $this->assertDatabaseHas('items', ['name' => 'Milk', 'fridge_id' => $fridge->id]);
    }

    public function test_user_can_mark_an_item_as_used(): void
    {
        $item = Item::factory()->create();

        $this->actingAs($item->fridge->owner)->post('/items/'.$item->id.'/use')->assertRedirect();

        $this->assertNotNull($item->fresh()->used_at);
    }
}
