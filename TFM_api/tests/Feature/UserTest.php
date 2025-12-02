<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users(): void
    {
        User::factory()->count(2)->create();
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/users');

        $response->assertOk()->assertJsonPath('data.total', 3);
    }

    public function test_user_can_update_own_profile(): void
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'New Name');
    }

    public function test_non_admin_cannot_delete_other_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$other->id}");

        $response->assertStatus(403);
    }
}
