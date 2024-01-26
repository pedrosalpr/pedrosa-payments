<?php

declare(strict_types=1);

namespace App\Services\Gateways\PaymentsProvider;

class PaymentProvider
{
    public function simulate(): bool
    {
        return rand(1, 100) <= 70;
    }
}
