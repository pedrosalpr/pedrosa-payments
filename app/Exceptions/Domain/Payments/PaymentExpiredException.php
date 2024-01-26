<?php

declare(strict_types=1);

namespace App\Exceptions\Domain\Payments;

use App\Enums\Payments\Status;

class PaymentExpiredException extends \Exception
{
    public static function paymentExpired(string $paymentId): self
    {
        return new self(
            sprintf(
                "The payment '%s' expired",
                $paymentId,
            )
        );
    }

    public function getStatus(): Status
    {
        return Status::EXPIRED;
    }
}
