<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WeddingTest extends TestCase
{
    use RefreshDatabase;

    private function makeService(): Service
    {
        $category = Category::factory()->create();
        $sub = Subcategory::factory()->create(['category_id' => $category->id]);
        $owner = User::factory()->create(['role' => 'business']);

        return Service::factory()->create([
            'subcategory_id' => $sub->id,
            'user_id' => $owner->id,
        ]);
    }

    public function test_user_can_create_wedding_and_attach_service(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $service = $this->makeService();

        Sanctum::actingAs($user);

        $create = $this->postJson('/api/weddings', [
            'name' => 'Mi boda',
            'guest_count' => 80,
        ]);

        $weddingId = $create->assertStatus(201)->json('data.id');

        $attach = $this->postJson("/api/weddings/{$weddingId}/services", [
            'service_id' => $service->id,
            'status' => 'consultado',
        ]);

        $attach->assertStatus(201);

        $this->assertDatabaseHas('wedding_service', [
            'wedding_id' => $weddingId,
            'service_id' => $service->id,
        ]);
    }

    public function test_non_owner_cannot_view_other_wedding(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);

        $wedding = Wedding::create([
            'user_id' => $owner->id,
            'name' => 'Privada',
            'status' => 'gestionando',
        ]);

        Sanctum::actingAs($other);

        $response = $this->getJson("/api/weddings/{$wedding->id}");

        $response->assertStatus(403);
    }
}
