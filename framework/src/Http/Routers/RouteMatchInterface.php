<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Http\RequestHandlerInterface;
use Framework\Http\Requests\RequestInterface;

interface RouteMatchInterface
{
    public function getRequestHandler(): RequestHandlerInterface;

    public function getRequest(): RequestInterface;
}
