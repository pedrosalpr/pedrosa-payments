<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Payments\PaymentContract;
use App\Models\Payments\Payment;

class PaymentRepository
{
    public function save(PaymentContract $payment): Payment
    {
        return Payment::create($payment->toModel());
    }
}
