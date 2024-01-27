<?php

declare(strict_types=1);

namespace App\Entities\Payments;

use App\Domain\PaymentsStatus\PaymentStatus;
use App\Entities\Clients\ClientContract;
use App\Entities\PaymentsMethods\PaymentMethod;
use App\Enums\Payments\Status;
use App\Models\User;
use Illuminate\Support\Carbon;

class Payment implements PaymentContract
{
    public function __construct(
        private readonly string $id,
        private readonly User $user,
        private readonly ClientContract $client,
        private readonly string $description,
        private readonly float $value,
        private PaymentStatus $status,
        private readonly PaymentMethod $paymentMethod,
        private readonly ?Carbon $dueDate,
        private readonly float $tax,
        private ?Carbon $processedAt = null,
        private ?Carbon $expiredAt = null,
    ) {
        $this->setStatus($status);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClient(): ClientContract
    {
        return $this->client;
    }

    public function getReponse(): array
    {
        return [
            'id' => $this->id,
            'client' => $this->getClient()->toArray(),
            'description' => $this->description,
            'value' => $this->value,
            'status' => $this->status->getStatus()->value,
            'payment_method' => $this->paymentMethod->slug,
            'due_date' => $this->dueDate
        ];
    }

    public function toModel(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'client_id' => $this->client->getId(),
            'description' => $this->description,
            'value' => $this->value,
            'status' => $this->status->getStatus()->value,
            'payment_method_id' => $this->paymentMethod->id,
            'due_date' => $this->dueDate,
            'tax' => $this->tax
        ];
    }

    public function setStatus(PaymentStatus $status): void
    {
        $this->status = $status;
        $this->status->setPayment($this);
    }

    public function process(bool $processed)
    {
        $this->status->process($processed);
        if ($this->isPaid() || $this->isFailed()) {
            $this->processedAt = Carbon::now();
        }
    }

    public function canProcess()
    {
        $processingAt = Carbon::now();
        $this->status->canProcess($processingAt);
        if ($this->isExpired()) {
            $this->expiredAt = $processingAt;
        }
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getDueDate(): ?Carbon
    {
        return $this->dueDate;
    }

    public function getProcessedAt(): ?Carbon
    {
        return $this->processedAt;
    }

    public function getExpiredAt(): ?Carbon
    {
        return $this->expiredAt;
    }

    public function getPaymentStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function calculateValueTax(): float
    {
        if ($this->status->getStatus() === Status::PAID) {
            $valueTax = $this->value * ($this->tax / 100);
            return (float) number_format($valueTax, 2);
        }
        return 0;
    }

    public function isPaid(): bool
    {
        return $this->status->getStatus() === Status::PAID;
    }

    public function isExpired(): bool
    {
        return $this->status->getStatus() === Status::EXPIRED;
    }

    public function isFailed(): bool
    {
        return $this->status->getStatus() === Status::FAILED;
    }

    public function isProcessing(): bool
    {
        return $this->status->getStatus() === Status::PROCESSING;
    }

    public function getDetailsPayment(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'client' => $this->client->toArray(),
            'description' => $this->description,
            'value' => $this->value,
            'status' => $this->status->getStatus()->value,
            'payment_method' => $this->paymentMethod->toArray(),
            'due_date' => $this->dueDate,
            'tax' => $this->tax,
            'processed_at' => $this->processedAt,
            'expired_at' => $this->expiredAt,
        ];
    }
}
