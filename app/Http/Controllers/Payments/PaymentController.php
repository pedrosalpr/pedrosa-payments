<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
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
        // Criar o registro do pagamento
        // e retorna uuid
        // Vamos chamar o process (Sync ou SQS)
        // Simulação do Sync chamar o process
        // Se retorna true, muda o status do pagamento para paid, e cobra a taxa
        return response()->json($payment->getReponse(), Response::HTTP_CREATED);
    }
}
