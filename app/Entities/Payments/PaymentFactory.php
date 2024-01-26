<?php

declare(strict_types=1);

namespace App\Entities\Payments;

use App\Domain\PaymentsStatus\PaymentStatus;
use App\Domain\PaymentsStatus\PaymentStatusPending;
use App\Entities\Clients\PrivateIndividual\PrivateIndividualFactory;
use App\Entities\PaymentsMethods\PaymentMethodFactory;
use App\Models\Payments\Payment as PaymentModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class PaymentFactory
{
    public static function createFromRegister(array $data): Payment
    {
        $description = Arr::get($data, 'description');
        $value = (float) Arr::get($data, 'value');
        $paymentDueDate = Carbon::parse(Arr::get($data, 'payment_due_date'));

        $client = PrivateIndividualFactory::createFromPayment(Arr::only($data, ['client']));

        $paymentMethod = PaymentMethodFactory::createFromSlug(Arr::get($data, 'payment_method'));

        return new Payment(
            (string) Uuid::uuid4(),
            Auth::user(),
            $client,
            $description,
            $value,
            new PaymentStatusPending(),
            $paymentMethod,
            $paymentDueDate,
            $paymentMethod->tax
        );
    }

    public static function createFromModel(PaymentModel $paymentModel): Payment
    {
        $client = PrivateIndividualFactory::createFromModel($paymentModel->client);
        $paymentMethod = PaymentMethodFactory::createFromModel($paymentModel->paymentMethod);
        return new Payment(
            $paymentModel->id,
            Auth::user(),
            $client,
            $paymentModel->description,
            $paymentModel->value,
            PaymentStatus::getPaymentStatus($paymentModel->status),
            $paymentMethod,
            $paymentModel->due_date,
            $paymentModel->tax,
            $paymentModel->processedAt,
            $paymentModel->expiredAt,
        );
    }
}
