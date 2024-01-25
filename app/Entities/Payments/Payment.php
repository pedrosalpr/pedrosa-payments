<?php

declare(strict_types=1);

namespace App\Entities\Payments;

use App\Entities\Clients\ClientContract;
use App\Entities\PaymentsMethods\PaymentMethod;
use App\Enums\Payments\Status;
use Illuminate\Support\Carbon;

class Payment implements PaymentContract
{
    public function __construct(
        public ?string $id = null,
        public ClientContract $client,
        public readonly string $description,
        public readonly float $value,
        public Status $status,
        public readonly PaymentMethod $paymentMethod,
        public readonly ?Carbon $dueDate
    ) {}

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
            'status' => $this->status->value,
            'payment_method' => $this->paymentMethod->slug,
            'due_date' => $this->dueDate
        ];
    }

    public function toModel(): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client->getId(),
            'description' => $this->description,
            'value' => $this->value,
            'status' => $this->status->value,
            'payment_method_id' => $this->paymentMethod->id,
            'due_date' => $this->dueDate
        ];
    }
}
