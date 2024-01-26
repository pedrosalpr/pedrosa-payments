<?php

declare(strict_types=1);

namespace Database\Factories\Payments;

use App\Models\Clients\Client;
use App\Models\Payments\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payments\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethod = PaymentMethod::factory()->pix()->make();
        return [
            'id' => fake()->uuid(),
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'value' => fake()->randomFloat(2, 0, 10000),
            'description' => fake()->text(255),
            'payment_method_id' => $paymentMethod->id,
            'tax' => $paymentMethod->tax,
            'due_date' => fake()->optional()->dateTimeBetween('now', '+10 days')
        ];
    }
}
