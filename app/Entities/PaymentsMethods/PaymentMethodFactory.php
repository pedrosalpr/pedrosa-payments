<?php

declare(strict_types=1);

namespace App\Entities\PaymentsMethods;

use App\Repositories\PaymentMethodRepository;

class PaymentMethodFactory
{
    public static function createFromSlug(string $slug): PaymentMethod
    {
        $paymentMethod = (new PaymentMethodRepository())->getPaymentMethodBySlug($slug);
        return new PaymentMethod(
            $paymentMethod->id,
            $paymentMethod->name,
            $paymentMethod->slug,
            $paymentMethod->tax
        );
    }
}
