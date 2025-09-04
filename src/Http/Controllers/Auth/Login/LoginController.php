<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Login;

use Framework\Http\Controllers\Attributes\Route;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\Middlewares\Auth\AuthenticationDisallowedMiddleware;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\HtmlResponse;
use Framework\Http\Responses\ResponseInterface;
use Framework\Views\ViewGeneratorInterface;
use Override;

#[Route(
    route: '/login',
    middlewareClasses: [
        AuthenticationDisallowedMiddleware::class,
    ]
)]
final readonly class LoginController implements ControllerInterface
{
    public function __construct(
        private ViewGeneratorInterface $viewGenerator,
    ) {}

    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        return new HtmlResponse(
            $this->viewGenerator->generate('auth/login')
        );
    }
}
