<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AuthRefreshTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/refresh';

    public function testShouldRefreshTokenAnUser(): void
    {
        $user = User::factory()->create();
        $tokenOld = JWTAuth::fromUser($user);
        $response = $this->actingAs($user, 'api')->postJson($this->uri);
        $response->assertOk()
            ->assertJsonPath('user.name', $user->name)
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('authorization.token', fn (string $token) => $token != $tokenOld);
        $this->assertAuthenticatedAs($user);
    }
}
