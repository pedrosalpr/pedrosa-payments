<?php

declare(strict_types=1);

namespace App\Enums\Identifiers;

enum IdentifierType: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';

    public static function tryFromType($value): ?self
    {
        return match ($value) {
            'CPF' => IdentifierType::CPF,
            'CNPJ' => IdentifierType::CNPJ
        };
    }

    public static function getType(): array
    {
        return array_map(
            fn (IdentifierType $type) => $type->value,
            IdentifierType::cases()
        );
    }
}
