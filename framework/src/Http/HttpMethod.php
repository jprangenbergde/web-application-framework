<?php

declare(strict_types=1);

namespace Framework\Http;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case DELETE = 'DELETE';
    case PUT = 'PUT';
    case PATCH = 'PATCH';

    public function is(HttpMethod $httpMethod): bool
    {
        return $this === $httpMethod;
    }

    public function isNot(HttpMethod $httpMethod): bool
    {
        return $this !== $httpMethod;
    }
}
