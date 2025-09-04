<?php

declare(strict_types=1);

namespace Framework\Http\Controllers\Attributes;

use Attribute;
use Framework\Http\Constraint;
use Framework\Http\HttpMethod;
use Framework\Http\Middlewares\MiddlewareInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Route
{
    /**
     * @param array<Constraint> $constraints
     * @param array<class-string<MiddlewareInterface>> $middlewareClasses
     */
    public function __construct(
        public string $route,
        public array $constraints = [],
        public HttpMethod $httpMethod = HttpMethod::GET,
        public array $middlewareClasses = [],
        public int $priority = 0,
    ) {}
}
