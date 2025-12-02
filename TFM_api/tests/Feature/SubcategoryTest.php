<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubcategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_list_subcategories(): void
    {
        $category = Category::factory()->create();
        Subcategory::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/subcategories');

        $response->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_only_admin_can_create_subcategory(): void
    {
        $category = Category::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/subcategories', [
            'name' => 'DJ',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201);

        Sanctum::actingAs(User::factory()->create(['role' => 'user']));
        $forbidden = $this->postJson('/api/subcategories', [
            'name' => 'Foto',
            'category_id' => $category->id,
        ]);
        $forbidden->assertStatus(403);
    }
}
