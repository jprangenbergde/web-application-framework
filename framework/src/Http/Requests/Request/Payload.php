<?php

declare(strict_types=1);

namespace Framework\Http\Requests\Request;

use Override;

final readonly class Payload implements PayloadInterface
{
    public function __construct(
        private array $payload
    ) {}

    #[Override]
    public function get(string $key): mixed
    {
        return $this->payload[$key] ?? null;
    }

    #[Override]
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->payload);
    }

    #[Override]
    public function toArray(): array
    {
        return $this->payload;
    }
}
