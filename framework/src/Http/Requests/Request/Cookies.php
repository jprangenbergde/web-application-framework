<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Override;

final class Cookies implements CookiesInterface
{
    public function __construct(
        private $cookies,
    ) {}

    #[Override]
    public function get(string $key): mixed
    {
        return $this->cookies[$key] ?? null;
    }

    #[Override]
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->cookies);
    }
}
