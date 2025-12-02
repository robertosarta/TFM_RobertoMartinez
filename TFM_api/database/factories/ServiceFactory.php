<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Subcategory;
use App\Models\Service;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessUser = User::where('role', 'business')->inRandomOrder()->first()
            ?? User::inRandomOrder()->first();

        $subcategory = Subcategory::inRandomOrder()->first();

        if (! $subcategory) {
            $category = Category::factory()->create();
            $subcategory = Subcategory::factory()->create(['category_id' => $category->id]);
        }

        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'zip' => $this->faker->postcode(),
            ],
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'user_id' => $businessUser ? $businessUser->id : null,
            'subcategory_id' => $subcategory->id,
        ];
    }
}
