<?php

declare(strict_types=1);

namespace App\Domain\PaymentsStatus;

use App\Enums\Payments\Status;
use App\Exceptions\Domain\Payments\PaymentForbiddenException;
use Illuminate\Support\Carbon;

class PaymentStatusFailed extends PaymentStatus
{
    public function getStatus(): Status
    {
        return Status::FAILED;
    }

    public function canProcess(Carbon $processedAt): void
    {
        throw PaymentForbiddenException::cannotProcessPaymentAfterFailed($this->payment->getId());
    }

    public function process(bool $processed): void
    {
        ($processed)
            ? $this->payment->setStatus(new PaymentStatusPaid())
            : $this->payment->setStatus(new PaymentStatusFailed());
    }

    public function expire(): void
    {
        throw PaymentForbiddenException::cannotExpirePaymentAfterFailed($this->payment->getId());
    }
}
