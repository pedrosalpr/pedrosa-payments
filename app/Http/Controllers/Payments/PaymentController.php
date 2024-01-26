<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\ProcessPaymentRequest;
use App\Http\Requests\Payments\RegisterPaymentRequest;
use App\Models\Payments\Payment;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
        $this->middleware('auth:api');
    }

    public function get(Payment $payment)
    {
        Gate::allowIf(fn () => Auth::user()->id === $payment->user_id);
        $paymentEntity = $this->paymentService->getPayment($payment);
        return response()->json($paymentEntity->getDetailsPayment());
    }

    public function list()
    {
        $user = Auth::user();
        $payments = $this->paymentService->listPayments($user->id);
        return response()->json($payments->all());
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
