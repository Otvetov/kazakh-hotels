<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'user']);
    }

    public function test_protected_route_requires_token(): void
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertUnauthorized();
    }

    public function test_protected_route_accessible_with_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/v1/user', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk();
    }

    public function test_banned_user_cannot_get_api_token(): void
    {
        $user = User::factory()->create(['banned_at' => now()]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }
}
