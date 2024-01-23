<?php

declare(strict_types=1);

namespace Tests\Feature\Health;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    private string $uri = '/api/login';

    public function testShouldLoginAnUser(): void
    {
        $password = 'teste123';
        $user = User::factory()->create([
            'password' => $password
        ]);
        $data = [
            'email' => $user->email,
            'password' => $password,
        ];
        $response = $this->postJson($this->uri, $data);
        $response->assertOk()
            ->assertJsonPath('user.name', $user->name)
            ->assertJsonPath('user.email', $user->email)
            ->assertJsonPath('authorization.token', fn (string $token) => strlen($token) >= 3);
        $this->assertAuthenticatedAs($user);
    }

    #[DataProvider('fieldsInvalidProvider')]
    public function testShouldReturnUnprocessableEntity($email, $password): void
    {
        $data = [
            'email' => $email,
            'password' => $password,
        ];
        $response = $this->postJson($this->uri, $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['status', 'type', 'title', 'detail', 'errors'])
            )
            ->assertHeader('content-type', 'application/problem+json');
    }

    public function testShouldReturnUnauthorizedWithUserNotExists(): void
    {
        $email = fake()->unique()->safeEmail();
        $password = fake()->password();
        $data = [
            'email' => $email,
            'password' => $password,
        ];
        $response = $this->postJson($this->uri, $data);
        $response->assertUnauthorized()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['status', 'type', 'title', 'detail'])
            )
            ->assertHeader('content-type', 'application/problem+json');
    }

    public function testShouldReturnUnauthorizedWithUserExistsAndPasswordIncorret(): void
    {
        $password = 'teste123';
        $user = User::factory()->create([
            'password' => $password
        ]);
        $data = [
            'email' => $user->email,
            'password' => $password . 'error',
        ];
        $response = $this->postJson($this->uri, $data);
        $response->assertUnauthorized()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['status', 'type', 'title', 'detail'])
            )
            ->assertHeader('content-type', 'application/problem+json');
    }

    public static function fieldsInvalidProvider(): array
    {
        $email = fake()->unique()->safeEmail();
        $password = fake()->password();
        return [
            'email invalid' => [null, $password],
            'password invalid' => [$email, null],
            'all fields invalid' => [null, null],
        ];
    }
}
