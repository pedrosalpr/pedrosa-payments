<?php

declare(strict_types=1);

namespace App\Entities\PaymentsMethods;

readonly class PaymentMethod
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public float $tax
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
