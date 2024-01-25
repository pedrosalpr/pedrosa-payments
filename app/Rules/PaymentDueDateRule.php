<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class PaymentDueDateRule implements ValidationRule
{
    public function __construct(private string $paymentMethod) {}

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if ($this->paymentMethod === 'boleto' && !$value) {
            $fail('The due date field is required');
        }
    }
}
