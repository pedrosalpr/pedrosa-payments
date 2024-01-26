<?php

declare(strict_types=1);

namespace App\Exceptions\Domain\Payments;

class PaymentForbiddenException extends \Exception
{
    public static function cannotProcessPaymentAfterFailed(string $paymentId): self
    {
        return new self(
            sprintf(
                'It is not allowed to process payment %s after it has failed',
                $paymentId,
            )
        );
    }

    public static function cannotProcessPaymentAfterPaid(string $paymentId): self
    {
        return new self(
            sprintf(
                'Cannot process payment %s that has already been paid'
            ),
            $paymentId
        );
    }

    public static function cannotProcessPaymentAfterExpired(string $paymentId): self
    {
        return new self(
            sprintf(
                'Cannot process payment %s that has already been expired'
            ),
            $paymentId
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

    public static function cannotExpirePaymentAfterExpired(string $paymentId): self
    {
        return new self(
            sprintf(
                'It is not allowed to expire payment %s after it has expired',
                $paymentId,
            )
        );
    }

    public static function cannotExpirePaymentAfterFailed(string $paymentId): self
    {
        return new self(
            sprintf(
                'It is not allowed to expire payment %s after it has expired',
                $paymentId,
            )
        );
    }

    public static function cannotExpirePaymentAfterPaid(string $paymentId): self
    {
        return new self(
            sprintf(
                'Cannot expire payment %s that has already been paid',
                $paymentId,
            )
        );
    }
}
