<?php

declare(strict_types=1);

namespace App\Entities\Clients;

use App\Enums\Identifiers\IdentifierType;

interface ClientContract
{
    public function getType(): IdentifierType;

    public function getIdentifier(): string;

    public function getName(): string;

    public function getId(): string;

    public function toModel(): array;

    public function toArray(): array;
}
