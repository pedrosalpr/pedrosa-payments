<?php

declare(strict_types=1);

namespace App\Exceptions\Domain\Payments;

use App\Enums\Payments\Status;

class PaymentFailedException extends \Exception
{
    public static function paymentFailed(string $paymentId): self
    {
        return new self(
            sprintf(
                "The payment '%s' failed",
                $paymentId,
            )
        );
    }

    public function getStatus(): Status
    {
        return Status::FAILED;
    }
}
