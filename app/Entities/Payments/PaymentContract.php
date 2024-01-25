<?php

declare(strict_types=1);

namespace App\Entities\Payments;

use App\Entities\Clients\ClientContract;

interface PaymentContract
{
    public function getClient(): ClientContract;

    public function getReponse(): array;

    public function toModel(): array;
}
