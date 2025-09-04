<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use Framework\Http\RequestHandlerInterface;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\ResponseInterface;
use Override;

final readonly class MiddlewareChain implements RequestHandlerInterface
{
    /**
     * @param array<MiddlewareInterface> $middlewares
     */
    public function __construct(
        private array $middlewares,
        private RequestHandlerInterface $requestHandler
    ) {}

    #[Override]
    public function handle(RequestInterface $request): ResponseInterface
    {
        if ($this->middlewares === []) {
            return $this->requestHandler->handle($request);
        }

        foreach ($this->middlewares as $index => $middleware) {
            $nextMiddleware = $this->middlewares[$index + 1] ?? null;
            if ($nextMiddleware) {
                $middleware->setNextHandler($nextMiddleware);
            } else {
                $middleware->setNextHandler($this->requestHandler);
            }
        }

        return $this->middlewares[array_key_first($this->middlewares)]->handle($request);
    }
}
