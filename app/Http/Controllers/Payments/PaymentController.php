<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\ProcessPaymentRequest;
use App\Http\Requests\Payments\RegisterPaymentRequest;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
        $this->middleware('auth:api');
    }

    public function register(RegisterPaymentRequest $request)
    {
        $payment = $this->paymentService->register($request->validated());
        return response()->json($payment->getReponse(), Response::HTTP_CREATED);
    }

    public function process(ProcessPaymentRequest $request)
    {
        $payment = $this->paymentService->process($request->validated());
        return response()->json([
            'payment_status' => $payment->getPaymentStatus()->getStatus()
        ]);
    }
}
