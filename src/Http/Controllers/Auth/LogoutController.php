<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Services\Auth\LogoutService;
use Framework\Http\Controllers\Attributes\Route;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\Middlewares\Auth\AuthenticationNeededMiddleware;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\HtmlResponse;
use Framework\Http\Responses\ResponseInterface;
use Framework\Views\ViewGeneratorInterface;
use Override;

#[Route(
    route: '/logout',
    middlewareClasses: [
        AuthenticationNeededMiddleware::class,
    ]
)]
final readonly class LogoutController implements ControllerInterface
{
    public function __construct(
        private ViewGeneratorInterface $viewGenerator,
        private LogoutService $logoutService
    ) {}

    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        $this->logoutService->logout($request->getSession());

        return new HtmlResponse(
            $this->viewGenerator->generate(
                'auth/logout',
                ['message' => 'Logged out successfully!']
            ),
        );
    }
}
