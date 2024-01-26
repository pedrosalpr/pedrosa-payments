<?php

declare(strict_types=1);

namespace App\Exceptions\Domain\Payments;

class PaymentStatusException extends \Exception
{
    public static function invalidProcessPaymentAfterPaid(string $paymentId): self
    {
        return new self(
            sprintf(
                'It is not allowed to process payment %s after it has paid',
                $paymentId,
            )
        );
    }

    public static function invalidProcessPaymentWhenInProcessing(string $paymentId): self
    {
        return new self(
            sprintf(
                'It is not allowed to process the payment %s when it is in processing',
                $paymentId,
            )
        );
    }
}
