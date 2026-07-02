<?php

namespace Tests\Feature;

use App\Models\Fridge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_invite_a_helper(): void
    {
        $fridge = Fridge::factory()->create();

        $this->actingAs($fridge->owner)
            ->post('/fridges/'.$fridge->id.'/shares', ['email' => 'helper@example.com'])
            ->assertRedirect();

        $this->assertDatabaseHas('shares', ['fridge_id' => $fridge->id, 'email' => 'helper@example.com', 'status' => 'pending']);
    }
}
