<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Users;

use App\Enums\Payments\Status;
use App\Models\Payments\Payment;
use App\Models\User;
use Database\Factories\Payments\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBalanceTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/users/balance';

    public function testShouldReturnForbiddenForUserNotAuthenticated(): void
    {
        $response = $this->getJson($this->uri);
        $response->assertUnauthorized();
    }

    public function testShouldReturnBalance(): void
    {
        $user = User::factory()->create();
        $payments = Payment::factory(3)->state(new Sequence(
            PaymentFactory::paidAttributes(),
            PaymentFactory::failAttributes(),
            PaymentFactory::paidAttributes(),
        ))->create([
            'user_id' => $user->id,
        ]);
        $balance = $payments->sum(function ($payment) {
            if ($payment->status === Status::PAID) {
                $valueTax = $payment->value * ($payment->tax / 100);
                return (float) number_format($valueTax, 2);
            }
            return 0;
        });
        $response = $this->actingAs($user, 'api')->getJson($this->uri);
        $response->assertOk()->assertJsonPath('balance', $balance);
    }

    public function testShouldReturnZeroWhenNotHasPaymentPaid(): void
    {
        $user = User::factory()->create();
        $payments = Payment::factory(2)->state(new Sequence(
            PaymentFactory::failAttributes(),
            PaymentFactory::expireAttributes(),
        ))->create([
            'user_id' => $user->id,
        ]);
        $balance = $payments->sum(function ($payment) {
            if ($payment->status === Status::PAID) {
                $valueTax = $payment->value * ($payment->tax / 100);
                return (float) number_format($valueTax, 2);
            }
            return 0;
        });
        $response = $this->actingAs($user, 'api')->getJson($this->uri);
        $response->assertOk()->assertJsonPath('balance', $balance);
    }
}
