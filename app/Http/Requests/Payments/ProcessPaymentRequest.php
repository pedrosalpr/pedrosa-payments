<?php

declare(strict_types=1);

namespace App\Http\Requests\Payments;

use App\Models\Payments\Payment;
use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id == Payment::find($this->get('payment_id'))?->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'payment_id' => 'required|uuid|exists:payments,id'
        ];
    }
}
