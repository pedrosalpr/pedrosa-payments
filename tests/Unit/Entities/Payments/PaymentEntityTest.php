<?php

declare(strict_types=1);

namespace Tests\Unit\Entities\Payments;

use App\Entities\Payments\PaymentFactory;
use App\Models\Payments\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentEntityTest extends TestCase
{
    use RefreshDatabase;

    public function testEntityPayment(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $clientName = fake()->name();
        $clientCpf = fake('pt_BR')->cpf();
        $paymentMethod = PaymentMethod::factory()->pix()->make();
        $data = [
            'client' => [
                'name' => $clientName,
                'cpf' => $clientCpf
            ],
            'description' => fake()->text(255),
            'value' => fake()->randomFloat(2, 0, 10000),
            'payment_method' => $paymentMethod->slug,
            'due_date' => date('Y-m-d')
        ];
        $payment = PaymentFactory::createFromRegister($data);
        $this->assertIsObject($payment);
    }
}
