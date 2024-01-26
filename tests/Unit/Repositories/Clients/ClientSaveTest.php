<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories\Clients;

use App\Entities\Clients\PrivateIndividual\PrivateIndividual;
use App\Enums\Identifiers\IdentifierType;
use App\Helpers\Helper;
use App\Models\Clients\Client;
use App\Repositories\ClientRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ClientSaveTest extends TestCase
{
    use RefreshDatabase;

    public function testSavePrivateIndividualClientInDatabase(): void
    {
        $name = fake()->name();
        $cpf = fake('pt_BR')->cpf();
        $id = (string) Uuid::uuid4();
        $privateIndividual = new PrivateIndividual(
            $id,
            $name,
            $cpf
        );
        $clientRepository = new ClientRepository();
        $clientRepository->save($privateIndividual);
        $this->assertDatabaseHas('clients', [
            'id' => $id,
            'name' => $name,
            'identifier' => Helper::justDigits($cpf),
            'type' => IdentifierType::CPF,
        ]);
    }

    public function testSavePrivateIndividualClientInDatabaseWithClient(): void
    {
        $client = Client::factory()->cpf()->create();
        $name = $client->name;
        $cpf = $client->identifier;
        $id = $client->id;
        $privateIndividual = new PrivateIndividual(
            $id,
            $name,
            $cpf
        );
        $clientRepository = new ClientRepository();
        $clientRepository->save($privateIndividual);
        $this->assertDatabaseHas('clients', [
            'id' => $id,
            'name' => $name,
            'identifier' => Helper::justDigits($cpf),
            'type' => IdentifierType::CPF,
        ]);
    }

    public function testSavePrivateIndividualClientInDatabaseWithClientDeleted(): void
    {
        $client = Client::factory()->cpf()->trashed()->create();
        $name = $client->name;
        $cpf = $client->identifier;
        $id = fake()->uuid();
        $privateIndividual = new PrivateIndividual(
            $id,
            $name,
            $cpf
        );
        $clientRepository = new ClientRepository();
        $clientRepository->save($privateIndividual);
        $this->assertDatabaseHas('clients', [
            'id' => $id,
            'name' => $name,
            'identifier' => Helper::justDigits($cpf),
            'type' => IdentifierType::CPF,
        ]);
    }
}
