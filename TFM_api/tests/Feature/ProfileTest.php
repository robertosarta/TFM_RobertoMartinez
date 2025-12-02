<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_profile_via_api(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone' => '111',
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
            'phone' => '222333444',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'New Name')
            ->assertJsonPath('data.phone', '222333444');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'phone' => '222333444',
        ]);
    }

    public function test_user_cannot_update_other_user_profile(): void
    {
        [$owner, $other] = User::factory()->count(2)->create();

        Sanctum::actingAs($owner);

        $response = $this->putJson("/api/users/{$other->id}", [
            'name' => 'Hacked',
        ]);

        $response->assertStatus(403);
    }

    public function test_empty_payload_returns_422(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}", []);

        $response->assertStatus(422);
    }
}
