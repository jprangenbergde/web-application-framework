<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Override;

final readonly class QueryParameters implements QueryParametersInterface
{
    public function __construct(
        private array $queryParameters
    ) {}

    #[Override]
    public function get(string $key): mixed
    {
        return $this->queryParameters[$key] ?? null;
    }

    #[Override]
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->queryParameters);
    }
}
