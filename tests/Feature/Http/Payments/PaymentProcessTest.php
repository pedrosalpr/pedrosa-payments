<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Payments;

use App\Enums\Payments\Status;
use App\Models\Payments\Payment;
use App\Models\Payments\PaymentMethod;
use App\Models\User;
use App\Services\Gateways\PaymentsProvider\PaymentProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaymentProcessTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/payments/process';

    public function testShouldReturnForbiddenWhenPaymentIsOtherUser(): void
    {
        $user = User::factory()->create();
        $payment = Payment::factory()->create();
        $data = [
            'payment_id' => $payment->id
        ];
        $response = $this->actingAs($user, 'api')->postJson($this->uri, $data);
        $response->assertForbidden();
    }

    #[DataProvider('fieldsProcessPaymentProvider')]
    public function testShouldProcessPaymentAsPaidInFirstSimulate($paymentMethod, $dueDate): void
    {
        $mock = $this->mock(PaymentProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('simulate')->andReturnTrue();
        });
        $this->processAsPaid($paymentMethod, $dueDate);
    }

    #[DataProvider('fieldsProcessPaymentProvider')]
    public function testShouldProcessPaymentAsPaidInSecondSimulate($paymentMethod, $dueDate): void
    {
        $mock = $this->mock(PaymentProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('simulate')->once()->andReturnFalse()
                ->shouldReceive('simulate')->once()->andReturnTrue();
        });
        $this->processAsPaid($paymentMethod, $dueDate);
    }

    #[DataProvider('fieldsProcessPaymentProvider')]
    public function testShouldProcessPaymentAsPaidInThirdSimulate($paymentMethod, $dueDate): void
    {
        $mock = $this->mock(PaymentProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('simulate')->once()->andReturnFalse()
                ->shouldReceive('simulate')->once()->andReturnFalse()
                ->shouldReceive('simulate')->once()->andReturnTrue();
        });
        $this->processAsPaid($paymentMethod, $dueDate);
    }

    public static function fieldsProcessPaymentProvider(): array
    {
        return [
            'pix and duedate' => [PaymentMethod::factory()->pix()->make(), date('Y-m-d')],
            'pix and without duedate' => [PaymentMethod::factory()->pix()->make(), null],
            'boleto and duedate' => [PaymentMethod::factory()->boleto()->make(), date('Y-m-d')],
            'bank transfer and duedate' => [PaymentMethod::factory()->transfer()->make(), date('Y-m-d')],
            'bank transer and without duedate' => [PaymentMethod::factory()->transfer()->make(), null],
        ];
    }

    #[DataProvider('fieldsProcessPaymentFailedProvider')]
    public function testShouldReturnsForbiddenWhenProcessPayment($paymentMethod, $dueDate, $status): void
    {
        $mock = $this->mock(PaymentProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('simulate')->andReturnFalse();
        });
        $user = User::factory()->create();
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'tax' => $paymentMethod->tax,
            'due_date' => $dueDate
        ]);
        $data = [
            'payment_id' => $payment->id
        ];
        $response = $this->actingAs($user, 'api')->postJson($this->uri, $data);
        $response->assertForbidden()
            ->assertJsonPath(
                'payment_status',
                $status->value
            )
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['status', 'type', 'title', 'detail', 'payment_status'])
            )
            ->assertHeader('content-type', 'application/problem+json');
        $this->assertDatabaseHas(
            'payments',
            ['status' => $status->value]
        );
    }

    public static function fieldsProcessPaymentFailedProvider(): array
    {
        return [
            'fail with pix and duedate' => [PaymentMethod::factory()->pix()->make(), date('Y-m-d'), Status::FAILED],
            'fail with pix and without duedate' => [PaymentMethod::factory()->pix()->make(), null, Status::FAILED],
            'fail with boleto and duedate' => [PaymentMethod::factory()->boleto()->make(), date('Y-m-d'), Status::FAILED],
            'fail with bank transfer and duedate' => [PaymentMethod::factory()->transfer()->make(), date('Y-m-d'), Status::FAILED],
            'fail with bank transer and without duedate' => [PaymentMethod::factory()->transfer()->make(), null, Status::FAILED],
            'expire with pix and duedate' => [PaymentMethod::factory()->pix()->make(), Carbon::yesterday(), Status::EXPIRED],
            'expire with boleto and duedate' => [PaymentMethod::factory()->boleto()->make(), Carbon::yesterday(), Status::EXPIRED],
            'expire with bank transfer and duedate' => [PaymentMethod::factory()->transfer()->make(), Carbon::yesterday(), Status::EXPIRED],
        ];
    }

    private function processAsPaid($paymentMethod, $dueDate)
    {
        $user = User::factory()->create();
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'tax' => $paymentMethod->tax,
            'due_date' => $dueDate
        ]);
        $data = [
            'payment_id' => $payment->id
        ];
        $response = $this->actingAs($user, 'api')->postJson($this->uri, $data);
        $status = Status::PAID->value;
        $response->assertOk()
            ->assertJson([
                'payment_status' => $status
            ]);
        $this->assertDatabaseHas(
            'payments',
            ['status' => $status]
        );
    }
}
