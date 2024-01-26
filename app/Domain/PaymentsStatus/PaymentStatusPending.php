<?php

declare(strict_types=1);

namespace App\Domain\PaymentsStatus;

use App\Enums\Payments\Status;
use Illuminate\Support\Carbon;

class PaymentStatusPending extends PaymentStatus
{
    public function getStatus(): Status
    {
        return Status::PENDING;
    }

    public function canProcess(Carbon $processedAt): void
    {
        if ($this->payment->getDueDate() && $processedAt->startOfDay()->greaterThan($this->payment->getDueDate())) {
            $this->payment->setStatus(new PaymentStatusExpired());
        } else {
            $this->payment->setStatus(new PaymentStatusProcessing());
        }
    }

    public function process(bool $processed): void {}

    public function expire(): void {}
}
