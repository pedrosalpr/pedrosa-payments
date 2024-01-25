<?php

declare(strict_types=1);

namespace Database\Factories\Payments;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payments\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'PIX',
            'slug' => 'pix',
            'tax' => 1.5
        ];
    }

    public function pix(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'PIX',
                'slug' => 'pix',
                'tax' => 1.5
            ];
        });
    }

    public function boleto(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Boleto',
                'slug' => 'boleto',
                'tax' => 2.0
            ];
        });
    }

    public function transfer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Bank Transfer',
                'slug' => 'bank-transfer',
                'tax' => 4.0
            ];
        });
    }
}
