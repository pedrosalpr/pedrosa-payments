<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Payments\PaymentContract;
use App\Models\Payments\Payment;

class PaymentRepository
{
    public function save(PaymentContract $payment): Payment
    {
        return Payment::create($payment->toModel());
    }

    public function update(PaymentContract $payment): void
    {
        $paymentModel = $this->getPaymentById($payment->getId());
        $paymentModel->processed_at = $payment->getProcessedAt();
        $paymentModel->expired_at = $payment->getExpiredAt();
        $paymentModel->status = $payment->getPaymentStatus()->getStatus();
        $paymentModel->save();
    }

    public function getPaymentById(string $paymentId): ?Payment
    {
        return Payment::find($paymentId);
    }
}
