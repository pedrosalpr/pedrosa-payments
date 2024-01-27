<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Payments;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaymentRegisterTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/payments';

    public function testShouldReturnUnauthorized(): void
    {
        $response = $this->postJson($this->uri);
        $response->assertUnauthorized();
    }

    #[DataProvider('fieldsRegisterProvider')]
    public function testShouldRegisterPayment($value, $paymentMethod, $dueDate): void
    {
        $user = User::factory()->create();
        $clientName = fake()->name();
        $clientCpf = fake()->cpf();

        $data = [
            'client' => [
                'name' => $clientName,
                'cpf' => $clientCpf
            ],
            'description' => fake()->text(255),
            'value' => $value,
            'payment_method' => $paymentMethod,
            'due_date' => $dueDate
        ];
        $response = $this->actingAs($user, 'api')->postJson($this->uri, $data);
        $response->assertCreated();
    }

    public static function fieldsRegisterProvider(): array
    {
        $value = fake()->randomFloat(2, 0, 10000);
        return [
            'value less than 1' => [0.9, 'pix', date('Y-m-d')],
            'pix and duedate' => [$value, 'pix', date('Y-m-d')],
            'pix and without duedate' => [$value, 'pix', null],
            'boleto and duedate' => [$value, 'boleto', date('Y-m-d')],
            'bank transfer and duedate' => [$value, 'bank-transfer', date('Y-m-d')],
            'bank transer and without duedate' => [$value, 'bank-transfer', null],
        ];
    }

    #[DataProvider('fieldsInvalidProvider')]
    public function testShouldReturnUnprocessableEntity($client, $description, $value, $paymentMethod, $dueDate): void
    {
        $user = User::factory()->create();
        $data = [
            'client' => $client,
            'description' => $description,
            'value' => $value,
            'payment_method' => $paymentMethod,
            'due_date' => $dueDate
        ];
        $response = $this->actingAs($user, 'api')->postJson($this->uri, $data);
        $response->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['status', 'type', 'title', 'detail', 'errors'])
            )
            ->assertHeader('content-type', 'application/problem+json');
    }

    public static function fieldsInvalidProvider(): array
    {
        $name = fake()->name();
        $cpf = fake('pt_BR')->cpf();
        $client = [
            'name' => $name,
            'cpf' => $cpf,
        ];
        $description = fake()->text(255);
        $value = fake()->randomFloat(2, 1, 10000);
        $paymentMethodPix = 'pix';
        $paymentMethodBoleto = 'boleto';
        $dueDate = date('Y-m-d');

        return [
            'client invalid' => [null, $description, $value, $paymentMethodPix, $dueDate],
            'name client invalid' => [['name' => null, 'cpf' => $cpf], $description, $value, $paymentMethodPix, $dueDate],
            'cpf client invalid' => [['name' => $name, 'cpf' => null], $description, $value, $paymentMethodPix, $dueDate],
            'cpf client wrong' => [['name' => $name, 'cpf' => '11111111122'], $description, $value, $paymentMethodPix, $dueDate],
            'description invalid' => [$client, null, $value, $paymentMethodPix, $dueDate],
            'description greather max character' => [$client, fake()->realTextBetween(256, 300, 1), $value, $paymentMethodPix, $dueDate],
            'value invalid' => [$client, $description, null, $paymentMethodPix, $dueDate],
            'value less 0' => [$client, $description, -2.00, $paymentMethodPix, $dueDate],
            'value equal 0' => [$client, $description, 0, $paymentMethodPix, $dueDate],
            'payment method invalid' => [$client, $description, $value, null, $dueDate],
            'payment method wrong' => [$client, $description, $value, 'abc', $dueDate],
            'payment method boleto without duedate' => [$client, $description, $value, $paymentMethodBoleto, null],
            'duedate invalid' => [$client, $description, $value, $paymentMethodPix, 'abc'],
            'duedate with date less today' => [$client, $description, $value, $paymentMethodPix, Carbon::yesterday()->format('Y-m-d')],
        ];
    }
}
