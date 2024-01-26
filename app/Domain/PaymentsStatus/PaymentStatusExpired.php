<?php

declare(strict_types=1);

namespace App\Domain\PaymentsStatus;

use App\Enums\Payments\Status;
use App\Exceptions\Domain\Payments\PaymentForbiddenException;
use Illuminate\Support\Carbon;

class PaymentStatusExpired extends PaymentStatus
{
    public function getStatus(): Status
    {
        return Status::EXPIRED;
    }

    public function canProcess(Carbon $processedAt): void
    {
        throw PaymentForbiddenException::cannotProcessPaymentAfterExpired($this->payment->getId());
    }

    public function process(bool $processed): void
    {
        throw PaymentForbiddenException::cannotProcessPaymentAfterExpired($this->payment->getId());
    }

    public function expire(): void
    {
        throw PaymentForbiddenException::cannotExpirePaymentAfterExpired($this->payment->getId());
    }
}
