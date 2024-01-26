<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Payments;

use App\Models\Payments\Payment;
use App\Models\User;
use Database\Factories\Payments\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentListTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/payments';

    public function testShouldListEmpty(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->getJson($this->uri);
        $response->assertOk()->assertJson([]);
    }

    public function testShouldListPayments(): void
    {
        $user = User::factory()->create();
        $payments = Payment::factory(3)->state(new Sequence(
            PaymentFactory::paidAttributes(),
            PaymentFactory::failAttributes(),
            PaymentFactory::expireAttributes(),
        ))->create([
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'api')->getJson($this->uri);
        $response->assertOk()->assertJsonCount(3);
    }
}
