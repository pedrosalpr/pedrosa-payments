<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Payments;

use App\Models\Payments\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentGetTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/payments';

    public function testShouldReturnUnauthorized(): void
    {
        $response = $this->postJson($this->uri);
        $response->assertUnauthorized();
    }

    public function testShouldReturnPayment(): void
    {
        $user = User::factory()->create();
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
        ]);
        $uri = $this->uri . '/' . $payment->id;
        $response = $this->actingAs($user, 'api')->getJson($uri);
        $response->assertOk()->assertJsonPath('id', $payment->id)->assertJsonPath('user_id', $user->id);
    }

    public function testShouldReturnForbidden(): void
    {
        $user = User::factory()->create();
        $payment = Payment::factory()->create([]);
        $uri = $this->uri . '/' . $payment->id;
        $response = $this->actingAs($user, 'api')->getJson($uri);
        $response->assertForbidden();
    }

    public function testShouldReturnNotFound(): void
    {
        $user = User::factory()->create();
        $uri = $this->uri . '/' . fake()->uuid();
        $response = $this->actingAs($user, 'api')->getJson($uri);
        $response->assertNotFound();
    }
}
