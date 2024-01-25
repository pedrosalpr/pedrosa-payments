<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Payments\PaymentMethod;

class PaymentMethodRepository
{
    public function getPaymentMethodBySlug(string $slug): PaymentMethod
    {
        return PaymentMethod::where('slug', $slug)->first();
    }
}
