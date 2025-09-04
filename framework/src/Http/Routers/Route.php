<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Http\Constraint;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\HttpMethod;
use Framework\Http\Middlewares\MiddlewareInterface;

final readonly class Route
{
    /**
     * @param array<Constraint> $constraints
     * @param class-string<ControllerInterface> $controllerClass
     * @param array<class-string<MiddlewareInterface>> $middlewareClasses
     */
    public function __construct(
        public string $route,
        public HttpMethod $httpMethod,
        public array $constraints,
        public string $controllerClass,
        public array $middlewareClasses,
        public int $priority,
    ) {}
}
