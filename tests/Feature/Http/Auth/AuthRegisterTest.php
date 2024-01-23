<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/register';

    public function testShouldRegisterAnUser(): void
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => fake()->password(),
        ];
        $response = $this->postJson($this->uri, $data);
        $response->assertCreated()
            ->assertJsonPath('user.name', $name)
            ->assertJsonPath('user.email', $email)
            ->assertJsonPath('authorization.token', fn (string $token) => strlen($token) >= 3);
    }

    #[DataProvider('fieldsInvalidProvider')]
    public function testShouldReturnUnprocessableEntity($name, $email, $password, $createUser): void
    {
        $user = ($createUser) ? User::factory()->create() : null;
        $data = [
            'name' => $name,
            'email' => (!$createUser) ? $email : $user->email,
            'password' => $password,
        ];
        $response = $this->postJson($this->uri, $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['status', 'type', 'title', 'detail', 'errors'])
            )
            ->assertHeader('content-type', 'application/problem+json');
    }

    public static function fieldsInvalidProvider(): array
    {
        $name = fake()->name();
        $email = fake()->unique()->safeEmail();
        $password = fake()->password();
        return [
            'name invalid' => [null, $email, $password, false],
            'email invalid' => [$name, null, $password, false],
            'password invalid' => [$name, $email, null, false],
            'password less than 6 char' => [$name, $email, '12345', false],
            'all fields invalid' => [null, null, null, false],
            'email exist' => [$name, $email, $password, true],
        ];
    }
}
