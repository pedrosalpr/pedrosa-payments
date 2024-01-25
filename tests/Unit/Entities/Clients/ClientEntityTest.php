<?php

declare(strict_types=1);

namespace Tests\Unit\Entities\Clients;

use App\Entities\Clients\PrivateIndividual\PrivateIndividualFactory;
use App\Enums\Identifiers\IdentifierType;
use App\Models\Clients\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientEntityTest extends TestCase
{
    use RefreshDatabase;

    public function testEntityPrivateIndividualByCreatePayment(): void
    {
        $name = fake()->name();
        $cpf = fake('pt_BR')->cpf();
        $data = [
            'client' => [
                'name' => $name,
                'cpf' => $cpf
            ]
        ];
        $clientEntity = PrivateIndividualFactory::createFromPayment($data);
        $this->assertEquals($name, $clientEntity->getName());
        $this->assertEquals(IdentifierType::CPF, $clientEntity->getType());
        $this->assertEquals($cpf, $clientEntity->getIdentifier());
        $this->assertNotEmpty($clientEntity->getId());
    }

    public function testEntityPrivateIndividualByCreatePaymentWithClient(): void
    {
        $client = Client::factory()->cpf()->create();
        $name = $client->name;
        $cpf = $client->identifier;
        $data = [
            'client' => [
                'name' => $name,
                'cpf' => $cpf
            ]
        ];
        $clientEntity = PrivateIndividualFactory::createFromPayment($data);
        $this->assertEquals($name, $clientEntity->getName());
        $this->assertEquals(IdentifierType::CPF, $clientEntity->getType());
        $this->assertEquals($cpf, $clientEntity->getIdentifier());
        $this->assertEquals($client->id, $clientEntity->getId());
    }

    public function testEntityPrivateIndividualByCreatePaymentWithClientAndNameDifferent(): void
    {
        $client = Client::factory()->cpf()->create();
        $name = $client->name;
        $cpf = $client->identifier;
        $data = [
            'client' => [
                'name' => fake()->name(),
                'cpf' => $cpf
            ]
        ];
        $clientEntity = PrivateIndividualFactory::createFromPayment($data);
        $this->assertEquals($name, $clientEntity->getName());
        $this->assertEquals(IdentifierType::CPF, $clientEntity->getType());
        $this->assertEquals($cpf, $clientEntity->getIdentifier());
        $this->assertEquals($client->id, $clientEntity->getId());
    }
}
