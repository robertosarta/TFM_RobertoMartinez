<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Review;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewTest extends TestCase
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

    public function test_user_can_create_review_once_per_service(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $service = $this->makeService();

        Sanctum::actingAs($user);

        $first = $this->postJson('/api/reviews', [
            'service_id' => $service->id,
            'rating' => 5,
            'comment' => 'Muy bien',
        ]);

        $first->assertStatus(201);

        $duplicate = $this->postJson('/api/reviews', [
            'service_id' => $service->id,
            'rating' => 4,
        ]);

        $duplicate->assertStatus(422);
    }

    public function test_reviews_index_can_filter_by_service(): void
    {
        $serviceA = $this->makeService();
        $serviceB = $this->makeService();
        $user = User::factory()->create(['role' => 'user']);

        Review::create([
            'service_id' => $serviceA->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'A',
        ]);
        Review::create([
            'service_id' => $serviceB->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'B',
        ]);

        $response = $this->getJson('/api/reviews?service_id=' . $serviceA->id);

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data.data');
    }
}
