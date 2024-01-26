<?php

declare(strict_types=1);

namespace App\Services\Users;

use App\Entities\Payments\PaymentFactory;
use App\Repositories\PaymentRepository;

class UserService
{
    public function __construct(
        private PaymentRepository $paymentRepository,
    ) {}

    public function balance($userId): float
    {
        $paymentsModelCollection = $this->paymentRepository->getPaymentsPaidByUserId($userId);
        return $paymentsModelCollection->map(function ($paymentModel) {
            $paymentEntity = PaymentFactory::createFromModel($paymentModel);
            return $paymentEntity->calculateValueTax();
        })->sum();
    }
}
