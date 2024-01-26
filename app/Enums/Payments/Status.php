<?php

declare(strict_types=1);

namespace App\Enums\Payments;

enum Status: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case EXPIRED = 'expired';
    case FAILED = 'failed';
    case PROCESSING = 'processing';

    public static function tryFromType($value): ?self
    {
        return match ($value) {
            'PENDING' => Status::PENDING,
            'PAID' => Status::PAID,
            'EXPIRED' => Status::EXPIRED,
            'FAILED' => Status::FAILED,
            'PROCESSING' => Status::PROCESSING,
        };
    }

    public static function getType(): array
    {
        return array_map(
            fn (Status $type) => $type->value,
            Status::cases()
        );
    }
}
