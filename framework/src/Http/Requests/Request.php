<?php

declare(strict_types=1);

namespace Framework\Http\Requests;

use Framework\Http\HttpMethod;
use Framework\Http\Requests\Request\Cookies;
use Framework\Http\Requests\Request\CookiesInterface;
use Framework\Http\Requests\Request\Payload;
use Framework\Http\Requests\Request\PayloadInterface;
use Framework\Http\Requests\Request\QueryParameters;
use Framework\Http\Requests\Request\QueryParametersInterface;
use Framework\Http\Requests\Request\RouteParameters;
use Framework\Http\Requests\Request\RouteParametersInterface;
use Framework\Http\Requests\Request\Server;
use Framework\Http\Requests\Request\ServerInterface;
use Framework\Http\Requests\Request\Session;
use Framework\Http\Requests\Request\SessionInterface;
use Override;

final class Request implements RequestInterface
{
    private RouteParametersInterface $routeParameters;

    public function __construct(
        private readonly PayloadInterface $payload,
        private readonly QueryParametersInterface $queryParameters,
        private readonly SessionInterface $session,
        private readonly CookiesInterface $cookies,
        private readonly ServerInterface $server,
    ) {}

    public static function fromGlobals(): self
    {
        return new self(
            new Payload($_POST),
            new QueryParameters($_GET),
            new Session($_SESSION),
            new Cookies($_COOKIE),
            new Server($_SERVER),
        );
    }

    #[Override]
    public function getHttpMethod(): HttpMethod
    {
        return HttpMethod::tryFrom($this->getServer()->get('REQUEST_METHOD')) ?? HttpMethod::GET;
    }

    #[Override]
    public function getRoute(): string
    {
        return parse_url((string) $this->getServer()->get('REQUEST_URI'))['path'] ?? '/';
    }

    #[Override]
    public function getPayload(): PayloadInterface
    {
        return $this->payload;
    }

    #[Override]
    public function getQueryParameters(): QueryParametersInterface
    {
        return $this->queryParameters;
    }

    #[Override]
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    #[Override]
    public function getCookies(): CookiesInterface
    {
        return $this->cookies;
    }

    #[Override]
    public function getServer(): ServerInterface
    {
        return $this->server;
    }

    #[Override]
    public function getRouteParameters(): RouteParametersInterface
    {
        return $this->routeParameters;
    }

    #[Override]
    public function setRouteParameters(array $routeParameters): void
    {
        $this->routeParameters = new RouteParameters($routeParameters);
    }
}
