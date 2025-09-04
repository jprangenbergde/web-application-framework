<?php

declare(strict_types=1);

namespace Framework\Http\Requests;

use Framework\Http\HttpMethod;
use Framework\Http\Requests\Request\CookiesInterface;
use Framework\Http\Requests\Request\PayloadInterface;
use Framework\Http\Requests\Request\QueryParametersInterface;
use Framework\Http\Requests\Request\RouteParametersInterface;
use Framework\Http\Requests\Request\ServerInterface;
use Framework\Http\Requests\Request\SessionInterface;

interface RequestInterface
{
    public function getHttpMethod(): HttpMethod;

    public function getRoute(): string;

    public function getPayload(): PayloadInterface;

    public function getQueryParameters(): QueryParametersInterface;

    public function getSession(): SessionInterface;

    public function getCookies(): CookiesInterface;

    public function getServer(): ServerInterface;

    public function getRouteParameters(): RouteParametersInterface;

    public function setRouteParameters(array $routeParameters): void;
}
