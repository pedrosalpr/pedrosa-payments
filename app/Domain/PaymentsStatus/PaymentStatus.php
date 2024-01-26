<?php

declare(strict_types=1);

namespace App\Domain\PaymentsStatus;

use App\Entities\Payments\Payment;
use App\Enums\Payments\Status;
use Illuminate\Support\Carbon;

abstract class PaymentStatus
{
    protected Payment $payment;

    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    abstract public function getStatus(): Status;

    abstract public function process(bool $processed): void;

    abstract public function expire(): void;

    abstract public function canProcess(Carbon $processedAt): void;

    public static function getPaymentStatus(Status $status): PaymentStatus
    {
        return match ($status) {
            Status::PENDING => new PaymentStatusPending(),
            Status::FAILED => new PaymentStatusFailed(),
            Status::PAID => new PaymentStatusPaid(),
            Status::EXPIRED => new PaymentStatusExpired(),
        };
    }
}
