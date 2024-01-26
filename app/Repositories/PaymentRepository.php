<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Payments\PaymentContract;
use App\Enums\Payments\Status;
use App\Models\Payments\Payment;
use Illuminate\Database\Eloquent\Collection;

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

    public function getPaymentsByUserId(string $userId): Collection
    {
        return Payment::where('user_id', $userId)->get();
    }

    public function getPaymentsPaidByUserId(string $userId): Collection
    {
        return Payment::where('user_id', $userId)->where('status', Status::PAID)->get();
    }
}
