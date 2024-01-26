<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Entities\Payments\PaymentContract;
use App\Entities\Payments\PaymentFactory;
use App\Exceptions\Domain\Payments\PaymentExpiredException;
use App\Exceptions\Domain\Payments\PaymentFailedException;
use App\Repositories\ClientRepository;
use App\Repositories\PaymentRepository;
use App\Services\Gateways\PaymentsProvider\PaymentProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    private const ATTEMPT = 3;

    public function __construct(
        private PaymentRepository $paymentRepository,
        private ClientRepository $clientRepository,
        private PaymentProvider $paymentProvider,
    ) {}

    public function register(array $data): PaymentContract
    {
        $payment = PaymentFactory::createFromRegister($data);
        $this->clientRepository->save($payment->getClient());
        $this->paymentRepository->save($payment);
        return $payment;
    }

    public function process(array $data): PaymentContract
    {
        $paymentId = Arr::get($data, 'payment_id');
        $paymentModel = $this->paymentRepository->getPaymentById($paymentId);
        $payment = PaymentFactory::createFromModel($paymentModel);
        $payment->canProcess();
        if ($payment->isProcessing()) {
            $payment = $this->attemptProcessPayment($payment);
        }
        $this->paymentRepository->update($payment);
        throw_if($payment->isExpired(), PaymentExpiredException::paymentExpired($payment->getId()));
        throw_if($payment->isFailed(), PaymentFailedException::paymentFailed($payment->getId()));
        return $payment;
    }

    public function listPayments(): Collection
    {
        $user = Auth::user();
        $paymentsModelCollection = $this->paymentRepository->getPaymentsByUserId($user->id);
        $paymentsEntityCollection = $paymentsModelCollection->map(function ($paymentModel) {
            $paymentEntity = PaymentFactory::createFromModel($paymentModel);
            return $paymentEntity->getDetailsPayment();
        });
        return $paymentsEntityCollection;
    }

    private function attemptProcessPayment(PaymentContract $payment): PaymentContract
    {
        $paymentAttempt = 1;
        do {
            $processed = $this->paymentProvider->simulate();
            $payment->process($processed);
            if ($payment->isPaid()) {
                break;
            }
            ++$paymentAttempt;
        } while ($paymentAttempt <= self::ATTEMPT);
        return $payment;
    }
}
