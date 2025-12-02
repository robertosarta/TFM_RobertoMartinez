<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_list_categories(): void
    {
        Category::factory()->count(2)->create();

        $response = $this->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/categories', ['name' => 'Banquete']);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Banquete');

        $this->assertDatabaseHas('categories', ['name' => 'Banquete']);
    }

    public function test_non_admin_cannot_create_category(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/categories', ['name' => 'Flores']);

        $response->assertStatus(403);
    }
}
