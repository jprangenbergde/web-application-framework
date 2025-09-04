<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares\Auth;

use Framework\Factories\Attributes\Config;
use Framework\Http\Middlewares\Middleware;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\RedirectResponse;
use Framework\Http\Responses\ResponseInterface;
use Override;

final class AuthenticationNeededMiddleware extends Middleware
{
    public function __construct(
        #[Config('auth.sessionKey', 'user')]
        private readonly string $sessionKey,
        #[Config('auth.redirectLocationIfUnauthenticated', '/login')]
        private readonly string $redirectLocation
    ) {}

    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        if (!$request->getSession()->has($this->sessionKey)) {
            return new RedirectResponse($this->redirectLocation);
        }

        return $this->getNextHandler()->handle($request);
    }
}
