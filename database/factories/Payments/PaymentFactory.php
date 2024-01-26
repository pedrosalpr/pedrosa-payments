<?php

declare(strict_types=1);

namespace Database\Factories\Payments;

use App\Enums\Payments\Status;
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
            'due_date' => fake()->optional()->dateTimeBetween('now', '+10 days'),
            'status' => Status::PENDING
        ];
    }

    public function paid(): Factory
    {
        return $this->state(function (array $attributes) {
            self::paidAttributes();
        });
    }

    public function failed(): Factory
    {
        return $this->state(function (array $attributes) {
            self::failAttributes();
        });
    }

    public function expired(): Factory
    {
        return $this->state(function (array $attributes) {
            self::expireAttributes();
        });
    }

    public static function paidAttributes(): array
    {
        return [
            'status' => Status::PAID,
            'processed_at' => now(),
        ];
    }

    public static function failAttributes(): array
    {
        return [
            'status' => Status::FAILED,
            'processed_at' => now(),
        ];
    }

    public static function expireAttributes(): array
    {
        return [
            'status' => Status::EXPIRED,
            'expired_at' => now(),
        ];
    }
}
