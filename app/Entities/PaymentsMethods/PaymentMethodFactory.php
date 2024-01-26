<?php

declare(strict_types=1);

namespace App\Entities\PaymentsMethods;

use App\Models\Payments\PaymentMethod as PaymentMethodModel;
use App\Repositories\PaymentMethodRepository;

class PaymentMethodFactory
{
    public static function createFromSlug(string $slug): PaymentMethod
    {
        $paymentMethod = (new PaymentMethodRepository())->getPaymentMethodBySlug($slug);
        return self::createFromModel($paymentMethod);
    }

    public static function createFromModel(PaymentMethodModel $paymentMethodModel): PaymentMethod
    {
        return new PaymentMethod(
            $paymentMethodModel->id,
            $paymentMethodModel->name,
            $paymentMethodModel->slug,
            $paymentMethodModel->tax
        );
    }
}
