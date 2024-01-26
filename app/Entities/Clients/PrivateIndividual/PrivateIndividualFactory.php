<?php

declare(strict_types=1);

namespace App\Entities\Clients\PrivateIndividual;

use App\Enums\Identifiers\IdentifierType;
use App\Helpers\Helper;
use App\Models\Clients\Client;
use App\Repositories\ClientRepository;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

class PrivateIndividualFactory
{
    public static function createFromPayment(array $data): PrivateIndividual
    {
        $cpf = Arr::get($data, 'client.cpf');
        $client = (new ClientRepository())->getClientByIdentifier(IdentifierType::CPF, Helper::justDigits($cpf));
        return new PrivateIndividual(
            $client?->id ?? (string) Uuid::uuid4(),
            $client?->name ?? Arr::get($data, 'client.name'),
            $cpf,
        );
    }

    public static function createFromModel(Client $client): PrivateIndividual
    {
        return new PrivateIndividual(
            $client->id,
            $client->name,
            $client->identifier,
        );
    }
}
