<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Login;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Framework\Http\Controllers\Attributes\Request;
use Framework\Http\Controllers\Attributes\Route;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\Controllers\Traits\CanValidateRequest;
use Framework\Http\HttpMethod;
use Framework\Http\Middlewares\Auth\AuthenticationDisallowedMiddleware;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\RedirectResponse;
use Framework\Http\Responses\ResponseInterface;
use Framework\Sessions\FlashMessengers\FlashMessage;
use Framework\Sessions\FlashMessengers\FlashMessengerInterface;
use InvalidArgumentException;
use Override;

#[Route(
    route: '/login',
    httpMethod: HttpMethod::POST,
    middlewareClasses: [
        AuthenticationDisallowedMiddleware::class,
    ]
)]
final readonly class LoginSubmitController implements ControllerInterface
{
    use CanValidateRequest;

    public function __construct(
        private FlashMessengerInterface $flashMessenger,
        private LoginService $loginService,
    ) {}

    #[Request(LoginRequest::class)]
    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        if (!$request instanceof LoginRequest) {
            throw new InvalidArgumentException();
        }

        $valid = $this->valid($request);
        if (!$valid) {
            return new RedirectResponse('/login');
        }

        return $this->login($request);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $loggedIn = $this->loginService->login(
            $request->email(),
            $request->password(),
            $request->getSession()
        );

        if (!$loggedIn) {
            $this->flashMessenger->addMessage(FlashMessage::fromText('Credentials incorrect!'));

            return new RedirectResponse('/login');
        }

        return new RedirectResponse('/');
    }
}
