<?php

declare(strict_types=1);

namespace Framework\Http\Controllers;

use Framework\Http\Controllers\Attributes\Route;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\EmptyResponse;
use Framework\Http\Responses\ResponseInterface;
use Override;

#[Route(
    route: '/health-check',
)]
final readonly class HealthCheckController implements ControllerInterface
{
    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        return new EmptyResponse();
    }
}
