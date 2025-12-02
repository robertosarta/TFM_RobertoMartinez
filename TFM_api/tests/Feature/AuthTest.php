<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_get_token(): void
    {
        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonPath('user.email', 'alice@example.com')
            ->assertJsonPath('token', fn ($token) => !empty($token));

        $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'bob@example.com',
            'password' => Hash::make('correct-pass'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'bob@example.com',
            'password' => 'wrong-pass',
        ]);

        $response->assertStatus(401);
    }
}
