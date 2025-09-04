<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Factories\Attributes\ImplementedBy;
use Framework\Http\Requests\RequestInterface;

#[ImplementedBy(Router::class)]
interface RouteMatcherInterface
{
    public function match(RequestInterface $request): RouteMatchInterface;
}
