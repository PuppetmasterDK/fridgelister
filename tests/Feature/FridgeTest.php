<?php

namespace Tests\Feature;

use App\Models\Fridge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FridgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_fridges_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/fridges')->assertOk();
    }

    public function test_user_can_create_a_fridge(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/fridges', ['name' => 'Kitchen'])->assertRedirect();

        $this->assertDatabaseHas('fridges', ['name' => 'Kitchen', 'user_id' => $user->id]);
    }

    public function test_fridge_page_loads(): void
    {
        $fridge = Fridge::factory()->create();

        $this->actingAs($fridge->owner)->get('/fridges/'.$fridge->id)->assertOk();
    }
}
