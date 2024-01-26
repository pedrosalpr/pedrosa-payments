<?php

declare(strict_types=1);

namespace App\Entities\Payments;

use App\Domain\PaymentsStatus\PaymentStatus;
use App\Entities\Clients\ClientContract;
use Illuminate\Support\Carbon;

interface PaymentContract
{
    public function getClient(): ClientContract;

    public function getReponse(): array;

    public function getDetailsPayment(): array;

    public function toModel(): array;

    public function process(bool $processed);

    public function canProcess();

    public function getTax(): float;

    public function getPaymentStatus(): PaymentStatus;

    public function calculateValueTax(): float;

    public function getDueDate(): ?Carbon;

    public function getProcessedAt(): ?Carbon;

    public function getExpiredAt(): ?Carbon;

    public function isPaid(): bool;

    public function isExpired(): bool;

    public function isFailed(): bool;

    public function getId(): string;
}
