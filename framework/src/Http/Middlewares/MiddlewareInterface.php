<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use Framework\Http\RequestHandlerInterface;

interface MiddlewareInterface extends RequestHandlerInterface
{
    public function setNextHandler(RequestHandlerInterface $nextHandler): void;
}
