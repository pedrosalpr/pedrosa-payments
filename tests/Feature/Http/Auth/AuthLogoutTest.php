<?php

declare(strict_types=1);

namespace Tests\Feature\Health;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLogoutTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/logout';

    public function testShouldLogoutAnUser(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->postJson($this->uri);
        $response->assertOk()
            ->assertJsonPath('message', 'Successfully logged out');
    }
}
