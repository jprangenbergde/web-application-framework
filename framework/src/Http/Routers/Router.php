<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Factories\Attributes\ProducedBy;
use Framework\FactoryManagers\Attributes\InstanceLocked;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Http\Constraint;
use Framework\Http\Controllers\Attributes\Request;
use Framework\Http\Controllers\ControllerInterface;
use Framework\Http\Middlewares\MiddlewareChain;
use Framework\Http\RequestHandlerInterface;
use Framework\Http\Requests\RequestInterface;
use RuntimeException;
use Override;

#[InstanceLocked]
#[ProducedBy(RouterFactory::class)]
final class Router implements RouterInterface
{
    /**
     * @var array<Route>
     */
    private array $routes = [];

    /**
     * @param class-string<ControllerInterface> $pageNotFoundControllerClass
     */
    public function __construct(
        private readonly FactoryProducerInterface $factoryProducer,
        private readonly string $pageNotFoundControllerClass,
    ) {}

    #[Override]
    public function assemble(string $controllerClass, array $parameters = []): string
    {
        $parameters = array_combine(
            array_map(
                static fn($value) => ':' . $value,
                array_keys($parameters)
            ),
            $parameters
        );

        foreach ($this->routes as $route) {
            if ($route->controllerClass === $controllerClass) {
                return strtr($route->route, $parameters);
            }
        }

        throw new RuntimeException(
            sprintf(
                'Could not assemble route for controller class "%s"',
                $controllerClass
            )
        );
    }

    #[Override]
    public function register(array $routes): void
    {
        $this->routes = array_merge_recursive($this->routes, $routes);
    }

    #[Override]
    public function match(RequestInterface $request): RouteMatchInterface
    {
        foreach ($this->routes as $route) {
            if (!$this->routeMatched($route, $request)) {
                continue;
            }

            return new RouteMatch(
                $this->decorateRequestHandler($route),
                $this->decorateRequest($route->controllerClass, $request)
            );
        }

        return new RouteMatch(
            $this->factoryProducer->get($this->pageNotFoundControllerClass),
            $this->decorateRequest($this->pageNotFoundControllerClass, $request)
        );
    }

    private function decorateRequestHandler(Route $route): RequestHandlerInterface
    {
        /** @var ControllerInterface $controller */
        $controller = $this->factoryProducer->get($route->controllerClass);

        $middlewareClasses = $route->middlewareClasses;
        if ($middlewareClasses === []) {
            return $controller;
        }

        return new MiddlewareChain(
            array_map(
                fn(string $middlewareClass) => $this->factoryProducer->get($middlewareClass),
                $middlewareClasses
            ),
            $controller
        );
    }

    /**
     * @param class-string<ControllerInterface> $controllerClass
     */
    private function decorateRequest(string $controllerClass, RequestInterface $request): RequestInterface
    {
        $requestAttribute = method_attribute($controllerClass, 'handle', Request::class);
        if (!$requestAttribute instanceof Request) {
            return $request;
        }

        return $this->factoryProducer->get($requestAttribute->requestClass);
    }

    private function routeMatched(Route $route, RequestInterface $request): bool
    {
        if ($request->getHttpMethod()->isNot($route->httpMethod)) {
            return false;
        }

        if ($route->constraints === [] && $request->getRoute() === $route->route) {
            return true;
        }

        if ($route->constraints !== [] && $this->constraintsMatched($route, $request)) {
            return true;
        }

        return false;
    }

    private function constraintsMatched(Route $route, RequestInterface $request): bool
    {
        $constraintsRegexPattern = $this->createConstraintsRegexPattern(
            $route->route,
            $route->constraints
        );

        if (empty($constraintsRegexPattern)) {
            $constraintsRegexPattern = '/ /';
        }

        if (preg_match($constraintsRegexPattern, $request->getRoute(), $matches)) {
            $request->setRouteParameters(array_intersect_key($matches, $route->constraints));

            return true;
        }

        return false;
    }

    /**
     * @param array<Constraint> $constraints
     */
    private function createConstraintsRegexPattern(string $route, array $constraints): string
    {
        if ($constraints === []) {
            return '/ /';
        }

        foreach ($constraints as $key => $constraint) {
            $route = str_replace(':' . $key, "(?<$key>" . $constraint->value . '[/]?)', $route);
        }

        return '/' . str_replace('/', '\/', $route) . '$/';
    }
}
