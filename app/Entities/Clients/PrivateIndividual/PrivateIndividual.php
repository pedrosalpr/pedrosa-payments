<?php

declare(strict_types=1);

namespace App\Entities\Clients\PrivateIndividual;

use App\Entities\Clients\ClientContract;
use App\Enums\Identifiers\IdentifierType;
use App\Helpers\Helper;

class PrivateIndividual implements ClientContract
{
    private IdentifierType $type;

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $cpf,
    ) {
        $this->type = IdentifierType::CPF;
    }

    public function getType(): IdentifierType
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentifier(): string
    {
        return $this->cpf;
    }

    public function toModel(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'identifier' => Helper::justDigits($this->cpf),
            'name' => $this->name,
        ];
    }

    public function toArray(): array
    {
        return $this->toModel();
    }
}
