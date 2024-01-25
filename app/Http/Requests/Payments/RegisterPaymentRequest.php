<?php

declare(strict_types=1);

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'client.name' => 'required|string',
            'client.cpf' => 'required|cpf',
            'description' => 'required|string|max:255',
            'value' => 'required|numeric|gt:0|decimal:0,2',
            'payment_method' => 'required|exists:App\Models\Payments\PaymentMethod,slug',
            'due_date' => $this->get('payment_method') === 'boleto' ? 'date|after_or_equal:today' : 'nullable|date|after_or_equal:today'
        ];
    }
}
