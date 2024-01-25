<?php

declare(strict_types=1);

namespace Database\Factories\Clients;

use App\Enums\Identifiers\IdentifierType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clients\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'type' => fake()->randomElement(IdentifierType::getType()),
            'identifier' => fake()->uuid(),
            'name' => fake()->name(),
        ];
    }

    public function cpf(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => IdentifierType::CPF,
                'identifier' => fake()->cpf(false),
            ];
        });
    }
}
