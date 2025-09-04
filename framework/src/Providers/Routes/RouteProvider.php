<?php

declare(strict_types=1);

namespace Framework\Providers\Routes;

use Framework\Factories\Attributes\Config;
use Framework\Http\Controllers\Attributes\Route;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\Routers\Route as HttpRoute;
use Framework\Providers\PathsProvider;
use Override;

final readonly class RouteProvider extends PathsProvider implements RouteProviderInterface
{
    public function __construct(
        #[Config('router.controllers.paths', [])]
        array $paths
    ) {
        parent::__construct($paths);
    }

    #[Override]
    public function provide(): array
    {
        $routes = $this->fromPathsRecursively(function (string $class): ?HttpRoute {
            if (!is_subclass_of($class, ControllerInterface::class)) {
                return null;
            }

            $routeAttribute = class_attribute($class, Route::class);
            if ($routeAttribute === null) {
                return null;
            }

            return new HttpRoute(
                $routeAttribute->route,
                $routeAttribute->httpMethod,
                $routeAttribute->constraints,
                $class,
                $routeAttribute->middlewareClasses,
                $routeAttribute->priority,
            );
        });

        usort($routes, fn(HttpRoute $a, HttpRoute $b) => $b->priority <=> $a->priority);

        return $routes;
    }
}
