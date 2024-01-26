<?php

declare(strict_types=1);

namespace App\Domain\PaymentsStatus;

use App\Enums\Payments\Status;
use App\Exceptions\Domain\Payments\PaymentForbiddenException;
use Illuminate\Support\Carbon;

class PaymentStatusPaid extends PaymentStatus
{
    public function getStatus(): Status
    {
        return Status::PAID;
    }

    public function canProcess(Carbon $processedAt): void
    {
        throw PaymentForbiddenException::cannotProcessPaymentAfterPaid($this->payment->getId());
    }

    public function process(bool $processed): void
    {
        throw PaymentForbiddenException::cannotProcessPaymentAfterPaid($this->payment->getId());
    }

    public function expire(): void
    {
        throw PaymentForbiddenException::cannotExpirePaymentAfterPaid($this->payment->getId());
    }
}
