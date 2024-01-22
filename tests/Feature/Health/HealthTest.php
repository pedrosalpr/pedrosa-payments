<?php

declare(strict_types=1);

namespace Tests\Feature\Health;

use Illuminate\Http\Response;
use Tests\TestCase;

class HealthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testReturnsASuccessfulReponse(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
