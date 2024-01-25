<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Entities\Payments\PaymentContract;
use App\Entities\Payments\PaymentFactory;
use App\Repositories\ClientRepository;
use App\Repositories\PaymentRepository;

class PaymentService
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private ClientRepository $clientRepository,
    ) {}

    public function register(array $data): PaymentContract
    {
        $payment = PaymentFactory::createFromRegister($data);
        $this->clientRepository->save($payment->getClient());
        $this->paymentRepository->save($payment);
        return $payment;
    }
}
