<?php

declare(strict_types=1);

namespace Tests\Unit\Repositores\Payments;

use App\Entities\Clients\ClientContract;
use App\Entities\Payments\Payment;
use App\Entities\PaymentsMethods\PaymentMethodFactory;
use App\Enums\Payments\Status;
use App\Models\Clients\Client;
use App\Repositories\PaymentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class PaymentSaveTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldSavePaymentInDatabase(): void
    {
        $client = Client::factory()->cpf()->create();
        $id = fake()->uuid();
        $mock = $this->mock(ClientContract::class, function (MockInterface $mock) use ($client) {
            $mock->shouldReceive('getId')->andReturn($client->id);
        });
        $description = fake()->text(255);
        $value = fake()->randomFloat(2, 0, 10000);
        $paymentMethod = PaymentMethodFactory::createFromSlug('pix');
        $paymentEntity = new Payment(
            $id,
            $mock,
            $description,
            $value,
            Status::PENDING,
            $paymentMethod,
            null
        );
        $paymentRepository = new PaymentRepository();
        $paymentRepository->save($paymentEntity);
        $this->assertDatabaseHas('payments', [
            'id' => $id,
            'value' => $value,
            'status' => Status::PENDING,
            'payment_method_id' => $paymentMethod->id,
            'due_date' => null,
            'client_id' => $client->id
        ]);
    }
}
