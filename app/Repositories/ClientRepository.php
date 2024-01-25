<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Clients\ClientContract;
use App\Enums\Identifiers\IdentifierType;
use App\Models\Clients\Client;

class ClientRepository
{
    public function getClientByIdentifier(IdentifierType $type, string $identifier): ?Client
    {
        return Client::where('type', $type)->where('identifier', $identifier)->first();
    }

    public function save(ClientContract $clientEntity): Client
    {
        $client = Client::find($clientEntity->getId());
        return ($client) ?: Client::create($clientEntity->toModel());
    }
}
