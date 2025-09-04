<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(Router::class)]
interface RouteAssemblerInterface
{
    public function assemble(string $controllerClass, array $parameters = []): string;
}
