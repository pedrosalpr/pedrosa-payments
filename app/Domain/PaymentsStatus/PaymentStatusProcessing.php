<?php

declare(strict_types=1);

namespace App\Domain\PaymentsStatus;

use App\Enums\Payments\Status;
use App\Exceptions\Domain\Payments\PaymentForbiddenException;
use Illuminate\Support\Carbon;

class PaymentStatusProcessing extends PaymentStatus
{
    public function getStatus(): Status
    {
        return Status::PROCESSING;
    }

    public function canProcess(Carbon $processedAt): void
    {
        throw PaymentForbiddenException::invalidProcessPaymentWhenInProcessing($this->payment->getId());
    }

    public function process(bool $processed): void
    {
        ($processed)
            ? $this->payment->setStatus(new PaymentStatusPaid())
            : $this->payment->setStatus(new PaymentStatusFailed());
    }

    public function expire(): void {}
}
