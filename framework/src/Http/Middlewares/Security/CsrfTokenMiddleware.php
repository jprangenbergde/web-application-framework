<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares\Security;

use Framework\Http\HttpMethod;
use Framework\Http\Middlewares\Middleware;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\ResponseInterface;
use Override;

final class CsrfTokenMiddleware extends Middleware
{
    public const string CSRF_TOKEN_NAME = 'csrf_token';

    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        if ($request->getHttpMethod()->is(HttpMethod::GET)) {
            return $this->createToken($request);
        }

        return $this->verifyToken($request);
    }

    private function createToken(RequestInterface $request): ResponseInterface
    {
        $request->getSession()->set(self::CSRF_TOKEN_NAME, bin2hex(random_bytes(35)));

        return $this->getNextHandler()->handle($request);
    }

    private function verifyToken(RequestInterface $request): ResponseInterface
    {
        $csrfToken = $request->getPayload()->get(self::CSRF_TOKEN_NAME);
        $sessionCsrfToken = $request->getSession()->get(self::CSRF_TOKEN_NAME);

        if ($csrfToken !== $sessionCsrfToken) {
            throw new CsrfTokenMismatched();
        }

        return $this->getNextHandler()->handle($request);
    }
}
