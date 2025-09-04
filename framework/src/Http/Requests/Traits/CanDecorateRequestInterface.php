<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Traits;

use Framework\Http\HttpMethod;
use Framework\Http\Requests\Request\CookiesInterface;
use Framework\Http\Requests\Request\PayloadInterface;
use Framework\Http\Requests\Request\QueryParametersInterface;
use Framework\Http\Requests\Request\RouteParametersInterface;
use Framework\Http\Requests\Request\ServerInterface;
use Framework\Http\Requests\Request\SessionInterface;
use Framework\Http\Requests\RequestInterface;

trait CanDecorateRequestInterface
{
    public function __construct(
        protected readonly RequestInterface $request
    ) {}

    public function getHttpMethod(): HttpMethod
    {
        return $this->request->getHttpMethod();
    }

    public function getRoute(): string
    {
        return $this->request->getRoute();
    }

    public function getPayload(): PayloadInterface
    {
        return $this->request->getPayload();
    }

    public function getQueryParameters(): QueryParametersInterface
    {
        return $this->request->getQueryParameters();
    }

    public function getSession(): SessionInterface
    {
        return $this->request->getSession();
    }

    public function getCookies(): CookiesInterface
    {
        return $this->request->getCookies();
    }

    public function getServer(): ServerInterface
    {
        return $this->request->getServer();
    }

    public function getRouteParameters(): RouteParametersInterface
    {
        return $this->request->getRouteParameters();
    }

    public function setRouteParameters(array $routeParameters): void
    {
        $this->request->setRouteParameters($routeParameters);
    }
}
