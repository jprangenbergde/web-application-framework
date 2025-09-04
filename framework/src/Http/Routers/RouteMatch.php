<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Http\RequestHandlerInterface;
use Framework\Http\Requests\RequestInterface;
use Override;

final readonly class RouteMatch implements RouteMatchInterface
{
    public function __construct(
        private RequestHandlerInterface $requestHandler,
        private RequestInterface $request,
    ) {}

    #[Override]
    public function getRequestHandler(): RequestHandlerInterface
    {
        return $this->requestHandler;
    }

    #[Override]
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
