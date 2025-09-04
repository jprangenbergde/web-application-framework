<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Override;

final class Session implements SessionInterface
{
    public function __construct(
        private &$session,
    ) {}

    #[Override]
    public function set(string $key, mixed $value): void
    {
        $this->session[$key] = $value;
    }

    #[Override]
    public function get(string $key): mixed
    {
        return $this->session[$key] ?? null;
    }

    #[Override]
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }

    #[Override]
    public function unset(string $key): void
    {
        unset($this->session[$key]);
    }
}
