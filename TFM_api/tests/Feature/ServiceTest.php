<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private function createSubcategory(): Subcategory
    {
        $category = Category::factory()->create();
        return Subcategory::factory()->create(['category_id' => $category->id]);
    }

    public function test_public_can_view_services_list(): void
    {
        Service::factory()->count(3)->create();

        $response = $this->getJson('/api/services');

        $response
            ->assertOk()
            ->assertJsonPath('data.per_page', 15);
    }

    public function test_business_can_create_service(): void
    {
        $business = User::factory()->create(['role' => 'business']);
        $subcategory = $this->createSubcategory();

        Sanctum::actingAs($business);

        $payload = [
            'name' => 'Catering Deluxe',
            'email' => 'catering@example.com',
            'phone' => '600000111',
            'price' => 1200,
            'description' => 'Cenas y cocteles',
            'subcategory_id' => $subcategory->id,
        ];

        $response = $this->postJson('/api/services', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Catering Deluxe')
            ->assertJsonPath('data.subcategory_id', $subcategory->id);

        $this->assertDatabaseHas('services', ['name' => 'Catering Deluxe']);
    }

    public function test_guest_cannot_create_service(): void
    {
        $response = $this->postJson('/api/services', [
            'name' => 'Foto',
            'email' => 'f@example.com',
            'phone' => '600',
            'price' => 10,
        ]);

        $response->assertStatus(401);
    }
}
