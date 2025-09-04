<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Override;

final readonly class Server implements ServerInterface
{
    public function __construct(
        private array $server
    ) {}

    #[Override]
    public function get(string $key): mixed
    {
        return $this->server[$key] ?? null;
    }

    #[Override]
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->server);
    }
}
