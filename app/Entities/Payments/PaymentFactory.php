<?php

declare(strict_types=1);

namespace App\Entities\Payments;

use App\Entities\Clients\PrivateIndividual\PrivateIndividualFactory;
use App\Entities\PaymentsMethods\PaymentMethodFactory;
use App\Enums\Payments\Status;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class PaymentFactory
{
    public static function createFromRegister(array $data): Payment
    {
        $description = Arr::get($data, 'description');
        $value = (float) Arr::get($data, 'value');
        $paymentDueDate = Carbon::parse(Arr::get($data, 'payment_due_date'));

        $client = PrivateIndividualFactory::createFromPayment(Arr::only($data, ['client']));

        $status = Status::PENDING;
        if ($statusValue = Arr::get($data, 'status')) {
            $status = Status::tryFrom($statusValue);
        }

        $paymentMethod = PaymentMethodFactory::createFromSlug(Arr::get($data, 'payment_method'));

        return new Payment(
            (string) Uuid::uuid4(),
            $client,
            $description,
            $value,
            $status,
            $paymentMethod,
            $paymentDueDate
        );
    }
}
