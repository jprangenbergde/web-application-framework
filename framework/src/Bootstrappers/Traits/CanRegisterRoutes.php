<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

use Framework\Http\Routers\RouteRegistrarInterface;

trait CanRegisterRoutes
{
    private function registerRoutes(array $routes): void
    {
        $this->factoryProducer
            ->get(RouteRegistrarInterface::class)
            ->register($routes);
    }
}
