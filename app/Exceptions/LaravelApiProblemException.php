<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Phpro\ApiProblem\Http\HttpApiProblem;

class LaravelApiProblemException extends \Exception
{
    private HttpApiProblem $apiProblem;
    private int $statusCode;

    public function __construct(private \Throwable $ex)
    {
        match (get_class($ex)) {
            ValidationException::class => $this->validation(),
            \UnhandledMatchError::class,\Exception::class => $this->default(),
            UnauthorizedException::class,AuthenticationException::class => $this->unauthorized()
        };
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(
            $this->apiProblem->toArray(),
            $this->statusCode,
            [
                'content-type' => 'application/problem+json'
            ]
        );
    }

    private function validation()
    {
        $this->statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $this->apiProblem = new HttpApiProblem($this->statusCode, [
            'detail' => $this->ex->getMessage(),
            'errors' => ($this->ex instanceof ValidationException) ? $this->ex->errors() : null
        ]);
    }

    private function default()
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        $this->apiProblem = new HttpApiProblem($this->statusCode, [
            'detail' => 'Error',
        ]);
    }

    private function unauthorized()
    {
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
        $this->apiProblem = new HttpApiProblem($this->statusCode, [
            'detail' => $this->ex->getMessage(),
        ]);
    }
}
