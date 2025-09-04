<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use BadMethodCallException;
use Framework\Http\RequestHandlerInterface;
use Override;

abstract class Middleware implements MiddlewareInterface
{
    private ?RequestHandlerInterface $nextHandler = null;

    #[Override]
    public function setNextHandler(RequestHandlerInterface $nextHandler): void
    {
        $this->nextHandler = $nextHandler;
    }

    public function getNextHandler(): RequestHandlerInterface
    {
        if ($this->nextHandler === null) {
            throw new BadMethodCallException('Middleware next handler has not been set.');
        }

        return $this->nextHandler;
    }
}
