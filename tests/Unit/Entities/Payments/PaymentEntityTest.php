<?php

declare(strict_types=1);

namespace Tests\Unit\Entities;

use App\Entities\Payments\PaymentFactory;
use App\Models\Payments\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testEntityPayment(): void
    {
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
